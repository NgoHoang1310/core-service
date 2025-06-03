<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends ApiController
{
    public function index(Request $request)
    {
        $query = Plan::query();
        $perPage = $request->input('per_page', 10);
        $plans = $query->paginate($perPage);

        return $this->successResponse($plans, 'Plans retrieved successfully');
    }
}
