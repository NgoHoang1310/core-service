<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $users = [
            'id' => 1,
            'name' => 'John Doe',
            'birthdate' => '1990-01-01',
            'email' => 'john@gmail.com'
        ];
        return response()->json($users);
    }
}
