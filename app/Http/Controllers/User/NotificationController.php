<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Requirement #38: User Notifications (/notifications).
     */
    public function index(Request $request): View
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->orderBy('id', 'desc')
            ->paginate(15);

        // Mark unread notifications as read
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('user.notifications', compact('notifications'));
    }
}
