<?php
namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Setting;
use App\Support\AboutContent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'session.driver' => 'array', 'cache.default' => 'array']);
        DB::purge('sqlite');
        Schema::create('settings', function (Blueprint $table) {
            $table->id(); $table->string('key')->unique(); $table->text('value')->nullable(); $table->string('group'); $table->timestamps();
        });
    }

    private function asAdmin(): static
    {
        $admin = new Admin(['name' => 'CMS Tester', 'email' => 'cms@example.test', 'status' => 'active', 'role' => 'Super Admin']);
        $admin->id = 1;
        return $this->actingAs($admin, 'admin');
    }

    public function test_public_page_matches_reference_copy_and_alias(): void
    {
        $this->get('/about')->assertOk()->assertSee('Smarter Parking')->assertSee('Tomorrow')->assertSee('How It All')->assertSee('10,000+')->assertSee('aria-current="page"', false);
        $this->get('/about-us')->assertRedirect('/about');
    }

    public function test_guests_cannot_read_or_save_editor(): void
    {
        $this->get('/admin/about')->assertRedirect('/admin/login');
        $this->put('/admin/about', ['content' => AboutContent::defaults()])->assertRedirect('/admin/login');
        $this->assertSame(0, Setting::count());
    }

    public function test_admin_can_edit_content_and_reorder_add_and_remove_rows(): void
    {
        $this->asAdmin()->get('/admin/about')->assertOk()->assertSee('Save About Us page');
        $content = AboutContent::defaults();
        $content['hero']['title'] = 'Parking for our city';
        $content['features'] = array_reverse(array_slice($content['features'], 1));
        $content['features'][] = ['title' => 'New feature', 'description' => 'New description', 'icon' => 'star'];
        $content['statistics'][0]['value'] = '42,000+';
        $content['socials'] = '__empty__';
        $this->put('/admin/about', ['content' => $content])->assertSessionHasNoErrors()->assertRedirect('/admin/about');
        $saved = AboutContent::current();
        $this->assertSame('24/7 Support', $saved['features'][0]['title']);
        $this->assertSame([], $saved['socials']);
        $this->get('/about')->assertOk()->assertSee('Parking for our city')->assertSee('New feature')->assertSee('42,000+')->assertDontSee('Secure &amp; Safe', false);
    }

    public function test_unsafe_links_and_invalid_images_are_rejected_without_saving(): void
    {
        $this->asAdmin();
        $content = AboutContent::defaults();
        $content['navigation'][0]['url'] = 'javascript:alert(1)';
        $this->put('/admin/about', ['content' => $content])->assertSessionHasErrors('content.navigation.0.url');
        $this->put('/admin/about', ['content' => AboutContent::defaults(), 'uploads' => ['hero_image' => UploadedFile::fake()->create('bad.php', 1, 'text/plain')]])->assertSessionHasErrors('uploads.hero_image');
        $this->assertSame(0, Setting::count());
    }

    public function test_image_upload_is_saved_and_rendered(): void
    {
        $this->asAdmin();
        $this->put('/admin/about', ['content' => AboutContent::defaults(), 'uploads' => ['hero_image' => UploadedFile::fake()->image('garage.png', 50, 50)]])->assertSessionHasNoErrors()->assertRedirect('/admin/about');
        $path = AboutContent::current()['hero']['image'];
        try {
            $this->assertStringStartsWith('uploads/about/', $path);
            $this->assertFileExists(public_path($path));
            $this->get('/about')->assertOk()->assertSee($path);
        } finally { if (is_file(public_path($path))) unlink(public_path($path)); }
    }

    public function test_content_is_escaped_and_disabled_admin_is_rejected(): void
    {
        $this->asAdmin();
        $content = AboutContent::defaults();
        $content['story']['description'] = '<script>alert(1)</script>';
        $this->put('/admin/about', ['content' => $content])->assertSessionHasNoErrors();
        $this->get('/about')->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
        auth('admin')->user()->status = 'inactive';
        $this->put('/admin/about', ['content' => AboutContent::defaults()])->assertRedirect('/admin/login');
    }
}
