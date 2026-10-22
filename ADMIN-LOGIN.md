# Admin login

- Sign in: `/admin/login`.
- Edit page: **System → Admin Login CMS**, at `/admin/login-page`.
- Preview while authenticated: `/admin/login-page/preview`.
- Change your email, optional username and password: **Admin Profile**.
- OTP / password recovery entry point: `/admin/login/otp`.

All reference page text, branding, logo, background, feature labels/icons, input labels/placeholders, button text, contact link, copyright and OTP instructions are editable. Settings are stored as `admin_login_content` in the existing `settings` table. Logo uploads accept PNG, JPG or WebP up to 2 MB; background uploads accept the same formats up to 8 MB. Existing uploaded files remain available when replaced.

The requested `admin@admin.com` account was provisioned with a hashed password. Existing administrator accounts were preserved. No credentials are prefilled or shown on the public page. Remember-me authentication, password visibility and email/username login work. Failed password attempts are limited; customer sessions and disabled administrators cannot sign in to the admin area.

## Email OTP

OTP is delivered through Laravel's configured mail service. This installation currently uses `MAIL_MAILER=log`, so the OTP page explains that delivery is unavailable and disables sending. Configure a real SMTP/API mailer in the environment before using email OTP. Password login is independent of mail configuration.

Codes expire after 10 minutes, are hashed in server-side cache, allow at most five incorrect attempts, and are single-use. Resending invalidates the previous challenge. Responses do not reveal whether an email belongs to an admin. After using OTP, password changes are available in Admin Profile. Authentication cache uses the file store, avoiding a dependency on a database cache table.

## Setup and verification

The additive migration `2026_09_23_120000_add_username_to_admins_table.php` adds an optional unique username. `AdminLoginPageSeeder` initializes content without overwriting existing edits and is registered in `DatabaseSeeder`.

Focused test command:

```sh
php vendor/phpunit/phpunit/phpunit tests/Feature/AdminLoginExperienceTest.php
```

Tests use SQLite in memory and fake mail. Browser verification covers 1536×1024 desktop and 390×844 mobile, image/font loading, overflow, password visibility, a real login, dashboard response, editor save and logout. Screenshots are in `scratch/admin-login-desktop.jpg` and `scratch/admin-login-mobile.jpg`.

## Assets and generation

- `public/images/admin-login/background.png`: built-in image_gen photo, newly generated to match the reference's mood and composition; not the original photo.
- `public/images/admin-login/logo.svg`: editable vector recreation of the reference parking mark.
- `public/css/admin-login.css`: responsive page styles.
- Fonts and icons reuse the locally hosted assets from the About page.

Final image prompt (built-in image_gen):

> Photorealistic vertical architectural night photo for SmartParking admin login background, 1024x1536. Luxury modern dark charcoal concrete parking garage entrance at night. Building facade and horizontal warm gold LED light strip, plants near entrance, tall slim black parking access kiosk with bright yellow edge and glowing stylized P parking icon in lower right foreground, yellow-white barrier arm across driveway, rear of black sedan entering garage to far right with red tail lights. Wet dark pavement reflects warm light. Composition keeps left half very dark and uncluttered for large white text to be overlaid separately. Garage structure at right half. Cinematic moody navy black and gold lighting. Small wall lettering on right 'A BETTER PARKING EXPERIENCE AWAITS'. No website interface, no logo in top left, no slogans in left area, no watermarks. Match a premium black/yellow smart parking brand.
