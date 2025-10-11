<?php

trait UpdatingObserverTrait
{
  protected static function boot()
  {

    parent::boot();

    // updating updated_by when model is updated
    static::updating(function ($model) {
      if (!$model->isDirty('updated_by')) {
        $model->updated_by = auth()->user()->id;
      }
    });
  }
}
