<?php

namespace App\Imports;

use App\Models\Region;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportRegion implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Region([
            'region_name' => $row[0],
            'description' => $row[2],
            'abbreviation' => $row[1],
            'created_at' => $row[3]
        ]);
    }
}
