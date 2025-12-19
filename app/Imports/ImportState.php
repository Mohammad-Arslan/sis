<?php

namespace App\Imports;

use App\Models\State;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportState implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new State([
            'state_name' => $row[0],
            'country_id' => $row[2],
            'created_at' => $row[3]
        ]);
    }
}
