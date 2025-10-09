<?php

namespace App\Traits;

trait BranchCodeTrait
{
  protected static function boot()
  {

    parent::boot();

    static::creating(function ($model) {
      if (!$model->isDirty('branch_code')) {
        $count = $model->max('branch_code');
        $newBranchCode = $count == 0 ? 7011 : $count + 1;
        $checkRepitition = $model->where('branch_code', $newBranchCode)->exists();
        while ($checkRepitition) {
          $newBranchCode += 1;
          $checkRepitition = $model->where('branch_code', $newBranchCode)->exists();
        }
        $model->branch_code = $newBranchCode;
      }
    });
  }
}
