<?php

namespace Ichynul\Labuilder;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Ichynul\Labuilder\Http\Controllers;

class LabuilderServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'labuilder');

        if ($this->app->runningInConsole()) {

            $this->publishes([__DIR__ . '/../config' => config_path()],
                'labuilder-config');

            $this->publishes(
                [__DIR__ . '/../resources/assets' => public_path('vendor/ichynul/labuilder')],
                'labuilder-views'
            );

            $this->publishes(
                [__DIR__ . '/../database/migrations' => database_path('migrations')],
                'labuilder-migrations'
            );

            $this->publishes(
                [__DIR__ . '/../database/seeds' => database_path('seeds')],
                'labuilder-seeds'
            );

            $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang')], 'labuilder-lang');
        }
    }

    public function register()
    {
        Route::group(['prefix' => config('labuilder.admin_prefix'), 'middleware' => config('labuilder.admin_middleware')], function () {
            //
            Route::get('attachments', Controllers\AttachmentController::class . '@index');
            Route::get('attachments/export', Controllers\AttachmentController::class . '@export');
            Route::get('attachments/afterSuccess', Controllers\AttachmentController::class . '@afterSuccess');
            //
            Route::get('imports/import', Controllers\ImportController::class . '@import');
            Route::get('imports/afterSuccess', Controllers\ImportController::class . '@afterSuccess');
            //
            Route::post('uploads/upfiles/{type}/{token}', Controllers\UploadController::class . '@upfiles');
            Route::get('uploads/ext/{type}', Controllers\UploadController::class . '@ext');
        });
    }
}
