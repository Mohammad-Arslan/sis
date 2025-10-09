<?php

namespace App\Imports;

use App\Models\Country;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportCountry implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Country([
            'country_name' => $row[0],
            'abbreviation' => $row[1],
            'country_code' => $row[2],
            'created_at'=>$row[3]
        ]);
    }
}
