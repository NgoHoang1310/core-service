<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends ApiController
{
    public function index(Request $request)
    {
        $query = Plan::query()->where('status', Plan::STATUS_ACTIVE);
        $perPage = $request->input('per_page', 10);
        $plans = $query->paginate($perPage);

        return $this->successResponse(PlanResource::collection($plans)->response()->getData(true), 'Plans retrieved successfully');
    }
}
