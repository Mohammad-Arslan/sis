<?php

namespace App\Traits;

use DateTimeInterface;

trait SerializeDateTrait
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y');
    }
}
