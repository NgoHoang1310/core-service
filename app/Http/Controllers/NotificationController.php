<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends ApiController
{
    public function index(Request $request)
    {
        $targetId = $request->get('target_id');
        $query = Notification::query()
            ->where('is_read', 0)
            ->where(function ($q) use ($targetId) {
                $q->where('type', Notification::BROADCAST)
                    ->orWhere(function ($q2) use ($targetId) {
                        $q2->where('type', '!=', Notification::BROADCAST)
                            ->where('target_id', $targetId);
                    });
            })
            ->orderBy('id', 'desc');
        $perPage = $request->input('per_page', 20);
        $notifications = $query->paginate($perPage);

        return $this->successResponse($notifications, 'Notifications retrieved successfully');
    }

    public function markAsRead(Notification $notification)
    {
        $notification->is_read = 1;
        $notification->save();

        return $this->successResponse($notification, 'Notification marked as read successfully');
    }
}
