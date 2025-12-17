<?php

namespace App\Exports;

use App\Models\Town;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportTown implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Town::all();
    }
}
