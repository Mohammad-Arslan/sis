<?php

namespace App\Exports;

use App\Models\ComClass;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportClass implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ComClass::all();
    }
}
