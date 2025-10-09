<?php

namespace App\Imports;

use App\Models\Section;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportSection implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Section([
            'section_name' => $row[0],
            'abbreviation' => $row[1],
            'description' => $row[2],
            'created_at'=>$row[3]
        ]);
    }
}
