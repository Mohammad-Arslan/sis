<?php

namespace App\Traits;

use App\Services\ActivityLoggerService;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            app(ActivityLoggerService::class)->logModelCreated($model);
        });

        static::updated(function (Model $model) {
            if ($model->wasChanged()) {
                app(ActivityLoggerService::class)->logModelUpdated($model);
            }
        });

        static::deleted(function (Model $model) {
            app(ActivityLoggerService::class)->logModelDeleted($model);
        });
    }
}
