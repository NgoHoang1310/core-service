<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getPublicFileUrl(string $path)
 * @see \App\Services\FirebaseService
 */
class Firebase extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\FirebaseService::class;
    }
}
