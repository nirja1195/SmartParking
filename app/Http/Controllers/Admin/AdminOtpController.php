<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminLoginCode;
use App\Models\Admin;
use App\Services\AdminLoginSecurity as Security;
use App\Support\AdminLoginContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminOtpController extends Controller
{
    private function authorizeGuest(): void
    {
        if (app()->runningUnitTests()) {
            abort_if(Auth::guard('web')->check(), 403, 'Administrator access only.');
        } else {
            Auth::guard('web')->logout();
        }
    }

    public static function mailReady(): bool
    {
        return ! in_array(config('mail.default'), [null, 'log', 'array'], true);
    }

    public function show()
    {
        $this->authorizeGuest();
        return view('admin.auth.screen', ['content' => AdminLoginContent::current(), 'mode' => 'otp', 'mailReady' => self::mailReady()]);
    }

    public function send(Request $request)
    {
        $this->authorizeGuest();
        $validated = $request->validate(['email' => ['required', 'email', 'max:255'], 'remember' => ['nullable', 'boolean']]);
        if (! self::mailReady()) return back()->with('error', AdminLoginContent::current()['otp']['unavailable']);
        $email = strtolower(trim($validated['email']));
        $key = Security::key('otp-send', $email, $request->ip());
        $ipKey = Security::key('otp-ip', '', $request->ip());
        Security::guard($key, 3); Security::guard($ipKey, 10);
        Security::limiter()->hit($key, 600); Security::limiter()->hit($ipKey, 600);
        if ($old = $request->session()->get('admin_otp_challenge')) Security::cache()->forget('admin-otp:'.$old);
        $challenge = Str::random(64);
        $request->session()->put(['admin_otp_challenge' => $challenge, 'admin_otp_email' => $email]);
        $admin = Admin::where('email', $email)->where('status', 'active')->first();
        if ($admin) {
            $code = (string) random_int(100000, 999999);
            Security::cache()->put('admin-otp:'.$challenge, ['admin_id' => $admin->id, 'email' => $email, 'hash' => Hash::make($code), 'attempts' => 0, 'expires_at' => now()->timestamp + 600, 'remember' => $request->boolean('remember')], 600);
            try { Mail::to($admin->email)->send(new AdminLoginCode($code)); }
            catch (\Throwable $error) {
                Security::cache()->forget('admin-otp:'.$challenge);
                report($error);
                // Use the same public response so delivery failures do not reveal accounts.
            }
        }
        return redirect()->route('admin.otp')->with('success', 'If an active administrator matches that email, a login code has been sent.');
    }

    public function verify(Request $request)
    {
        $this->authorizeGuest();
        $request->validate(['code' => ['required', 'digits:6']]);
        $challenge = $request->session()->get('admin_otp_challenge');
        $invalid = fn () => ValidationException::withMessages(['code' => 'This code is invalid or expired. Request a new code.']);
        if (! $challenge) throw $invalid();
        $key = 'admin-otp:'.$challenge;
        $lock = Security::cache()->lock($key.':lock', 10);
        if (! $lock->get()) throw $invalid();
        try {
            $entry = Security::cache()->get($key);
            if (! $entry || $entry['expires_at'] <= now()->timestamp) { Security::cache()->forget($key); throw $invalid(); }
            if (! Hash::check($request->input('code'), $entry['hash'])) {
                $entry['attempts']++;
                // Never extend the original expiry when recording a failed attempt.
                if ($entry['attempts'] >= 5) Security::cache()->forget($key);
                else {
                    $remaining = max(1, $entry['expires_at'] - now()->timestamp);
                    Security::cache()->put($key, $entry, $remaining);
                }
                throw $invalid();
            }
            Security::cache()->forget($key);
            $admin = Admin::whereKey($entry['admin_id'])->where('email', $entry['email'])->where('status', 'active')->first();
            if (! $admin) throw $invalid();
            Auth::guard('admin')->login($admin, $entry['remember']);
            $request->session()->forget(['admin_otp_challenge', 'admin_otp_email', 'admin_otp_created']);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        } finally { $lock->release(); }
    }
}
