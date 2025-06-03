<?php

namespace App\Providers;

use App\Services\FirebaseService;
use Illuminate\Support\ServiceProvider;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FirebaseService::class, function ($app) {
            $auth = Firebase::auth();
            $storage = Firebase::storage();
            $bucket = $storage->getBucket(config('firebase.storage.default_bucket'));
            return new FirebaseService($auth, $storage, $bucket);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
