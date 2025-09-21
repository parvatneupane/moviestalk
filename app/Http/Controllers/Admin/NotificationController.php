<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found.',
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Notification deleted.',
        ]);
    }

public function markAsRead(Request $request)
{
    $request->validate([
        'notification_id' => 'required|exists:notifications,id',
    ]);

    $notification = auth()->user()->notifications()->find($request->notification_id);
    if($notification){
        $notification->markAsRead(); // ✅ Marks as read, does NOT delete
    }

    return response()->json(['success' => true]);
}

}
