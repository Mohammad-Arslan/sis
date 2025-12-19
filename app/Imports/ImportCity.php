<?php

namespace App\Imports;

use App\Models\City;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportCity implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new City([
            'city_name' => $row[0],
            'abbreviation' => $row[1],
            'state_id' => $row[2],
            'created_at' => $row[3]
        ]);
    }
}
