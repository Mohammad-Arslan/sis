<?php

namespace App\Traits;

trait CreatingObserverTrait
{
    protected static function boot()
    {

        parent::boot();

      // updating created_by when model is created
        static::creating(function ($model) {
            if (! $model->isDirty('created_by')) {
                $model->created_by = auth()->user()->id;
            }
        });
    }
}
