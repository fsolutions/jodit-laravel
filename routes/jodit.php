<?php

use Do6po\LaravelJodit\Http\Controllers\JoditController;
use Illuminate\Support\Facades\Route;

$route = Route::middleware(config('jodit.middlewares'));

if ($prefix = config('jodit.routes.prefix')) {
    $route = $route->prefix($prefix);
}

$route->group(function () {
    Route::post(config('jodit.routes.browse_path'), [JoditController::class, 'browse'])
        ->name(config('jodit.routes.browse_name'));

    Route::post(config('jodit.routes.upload_path'), [JoditController::class, 'upload'])
        ->name(config('jodit.routes.upload_name'));
});
