<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NotifyUser;
use App\Notifications\TestEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }


    public function read($id)
    {
        $notification = Auth::user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return back();
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }

    //  send email-notification
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $users = User::all();

        $data = [
            'title' => $request->message,
            'enrollmentText' => 'View Content',
            'url' => url('/'),
            'thankyou' => 'You have a new notification',
        ];

        Notification::send($users, new NotifyUser($data));

        return redirect()->with('success', 'Notification sent successfully.');
    }

    // Delete notification
    public function destroy($id)
    {
        Auth::user()->notifications()->where('id', $id)->firstOrFail()->delete();
        return redirect()->with('success', 'Notification deleted successfully.');
    }
}
