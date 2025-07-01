<?php

namespace App\Http\Controllers;

use App\Models\Subscription;

class SubscriptionController extends ApiController
{
    public function showByUserUuid(string $uuid)
    {
        $subscription = Subscription::query()
            ->with('plan')
            ->where('user_uuid', $uuid)
            ->where('status', Subscription::STATUS_ACTIVE)
            ->first();

        return $this->successResponse($subscription, 'Subscription retrieved successfully');
    }
}
