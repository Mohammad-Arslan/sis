<?php

namespace App\Imports;

use App\Models\Town;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportTown implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Town([
            'town_name' => $row[0],
            'state_id' => $row[2],
            'created_at' => $row[3]
        ]);
    }
}
