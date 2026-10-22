# About Us page

Public page: `/about` (`/about-us` redirects here).
Admin editor: `/admin/about`, also available under **System → About Us CMS**.

Edit branding, SEO, navigation, every heading/paragraph/badge, photo and alt text, mission/vision, feature icons, statistics, footer, app labels, social and support links. Features, statistics and link lists support adding, removing and reordering rows. Save publishes all changes together. Image uploads accept JPG, PNG and WebP up to 5 MB.

Content is stored in the existing `settings` table under `about_page_content`. No additional schema migration is needed on an existing installation. `AboutPageSeeder` creates the initial content without overwriting edits. For a new installation, run the usual application migrations and seeders; to initialize only this page run `php artisan db:seed --class=AboutPageSeeder`.

Support/legal links and social/app-store destinations are editable starter values. Set these to the organization's real destinations in the editor. Legal documents were not supplied, so the initial Terms and Privacy links point to the home page footer rather than invented legal pages.

## Verification

`php vendor/phpunit/phpunit/phpunit tests/Feature/AboutPageTest.php`

Tests use an isolated SQLite in-memory database, never the configured MySQL content. Coverage includes guest and disabled-admin restrictions, public rendering, persisted edits and ordering/deletion, HTML escaping, URL validation and image uploads. Browser checks cover 1024px desktop and 390px mobile with no horizontal overflow and successful image/font loading.

## Image assets

Photos are newly generated interpretations of the supplied reference, not its original photos. Built-in image_gen was used. Saved assets:

- `public/images/about/hero.png`
- `public/images/about/story.png`
- `public/images/about/difference.png`

Final prompts:

**Hero:** Generate a photorealistic wide landscape website hero photo, 1536x1024. Modern underground parking garage, polished concrete, yellow square support pillars with black P2 parking designation, several silver and black sedans parked on right, open bright spacious driving aisle to left, realistic fluorescent overhead lights. Match upscale yellow/black SmartParking website photography. No graphics, no badges, no added text except P2 on pillar. Photo only.

**Story:** Photorealistic landscape photograph for SmartParking About Us story section. Modern charcoal gray parking building exterior, bright yellow architectural vertical tower on the right with white P sign on black square, PARKING sign above entrance, yellow black boom barriers, lush green trees, clear blue sky, sunlit clean paved driveway. Wide 3:2 composition. No people, no badges, no graphic overlays. Match premium black and yellow parking website.

**Difference:** Photorealistic landscape 3:2 photograph for SmartParking About Us feature section. View from behind of young adult man with short dark hair wearing dark navy jacket and black backpack standing at left foreground in a modern parking garage, holding smartphone in right hand showing dark navy parking app with yellow P logo. Yellow pillars and parked black cars in background on right. No overlay text or badges, leave upper right garage wall uncluttered for a white text badge added in HTML. Professional natural commercial photography.

Inter and Caveat fonts are served locally from `public/fonts/about`, obtained from Google Fonts. Bootstrap Icons 1.11.3 are served locally with their stylesheet copyright notice retained. The page has no dependency on external font or icon requests.
