<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class AdminNotificationController extends Controller
{

    public function index()
    {
        $admin = User::find(auth()->id());
        $notifications = $admin->notifications;
        return response()->json([
            'notifications' => $notifications,
        ]);
    }
    public function notification($id)
    {
        $admin = User::find(auth()->id());
        $notification = $admin->notifications->where('id', $id)->first();
        if ($notification) {
            $postData = $notification->data['post'];
            $notificationInfo = [
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
                'id' => $notification->id,
            ];
            $resultArray = array_merge($postData, $notificationInfo);
            return $resultArray;
        }
        return "null";
    }
    public function countUnreadNotifications()
    {
        $admin = User::find(auth()->id());
        $countNotifications = $admin->unreadNotifications->count();
        return $countNotifications;
    }
    public function unreadNotifications()
    {
        $admin = User::find(auth()->id());
        return response()->json([
            'unreadNotifications' => $admin->unreadNotifications
        ]);
    }
    public function markAllAsRead()
    {
        $admin = User::find(auth()->id());
        $admin->unreadNotifications->markAsRead();
        // $admin->unreadNotifications()->all()->update(['read_at' => now()]);
        return response()->json([
            'markAsRead' => 'success'
        ]);
    }
    public function markAsReadById($id)
    {
        $admin = User::find(auth()->id());
        if ($admin->unreadNotifications()->where('id', $id)->first()) {
            $admin->unreadNotifications()->where('id', $id)->update(['read_at' => now()]);
            return response()->json([
                'markAsReadById' => 'success'
            ]);
        }
        return "not exist";
    }
    public function deleteAll()
    {
        $admin = User::find(auth()->id());
        $admin->notifications()->delete();
        return response()->json([
            'deleteAll' => 'success delete'
        ]);
    }
    public function deleteById($id)
    {
        $admin = User::find(auth()->id());
        $admin->notifications()->where('id', $id)->delete();
        return response()->json([
            'deleteAll' => 'success delete'
        ]);
    }
}
