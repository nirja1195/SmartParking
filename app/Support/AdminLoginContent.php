<?php

namespace App\Support;

use App\Models\Setting;

class AdminLoginContent
{
    public static function defaults(): array
    {
        return [
            'branding' => ['name' => 'Smart', 'highlight' => 'Parking', 'tagline' => 'Smart Parking', 'tagline_suffix' => 'Live, Easy.', 'logo' => 'images/admin-login/logo.svg'],
            'hero' => ['title' => "Smarter\nParking\nfor a Better", 'highlight' => 'Tomorrow', 'description' => 'Manage. Monitor. Simplify.', 'image' => 'images/admin-login/background.png', 'alt' => 'Secure modern parking entrance at night', 'footer' => "PARK SAFE\nDRIVE HAPPY"],
            'feature_1' => ['icon' => 'car-front-fill', 'label' => "Real-time\nAvailability"],
            'feature_2' => ['icon' => 'shield-shaded', 'label' => "Secure\n& Safe"],
            'feature_3' => ['icon' => 'bar-chart-fill', 'label' => "Smart\nManagement"],
            'form' => ['page_title' => 'Admin Login - SmartParking', 'new_here' => 'New here?', 'contact_label' => 'Contact Admin', 'contact_url' => 'mailto:admin@admin.com', 'title' => 'Welcome Back', 'description' => 'Login to access your admin panel', 'email_label' => 'Email / Username', 'email_placeholder' => 'Enter your email or username', 'password_label' => 'Password', 'password_placeholder' => 'Enter your password', 'remember_label' => 'Remember me', 'forgot_label' => 'Forgot password?', 'submit_label' => 'Login', 'divider' => 'OR', 'otp_label' => 'Login with Admin OTP', 'copyright' => '© 2026 SmartParking. All rights reserved.'],
            'otp' => ['title' => 'Admin OTP Login', 'description' => 'Enter your administrator email to receive a one-time login code.', 'send_label' => 'Send login code', 'code_label' => 'One-time code', 'code_placeholder' => 'Enter the 6-digit code', 'verify_label' => 'Verify & Login', 'back_label' => 'Back to password login', 'help' => 'Codes expire after 10 minutes. After signing in, you can change your password from Profile.', 'unavailable' => 'Email delivery is not configured yet. Please use your password or contact the administrator.'],
        ];
    }

    public static function current(): array
    {
        $stored = json_decode(Setting::get('admin_login_content', '{}'), true) ?: [];
        return array_replace_recursive(self::defaults(), $stored);
    }
}
