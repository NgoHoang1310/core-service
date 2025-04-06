<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});

Route::get('/sign-in', function () {
    // View::addLocation(resource_path('site'));
    return view('auth/sign-in');
});

Route::get('/sign-up', function () {
    // View::addLocation(resource_path('site'));
    return view('auth/sign-up');
});
