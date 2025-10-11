<?php

namespace App\Imports;

use App\Models\ComClass;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportClass implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ComClass([
            'class_name' => $row[0],
            'abbreviation' => $row[1],
            'description' => $row[2],
            'created_at'=>$row[3]
        ]);
    }
}
