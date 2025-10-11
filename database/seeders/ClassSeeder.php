<?php

namespace Database\Seeders;

use App\Models\ComClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = [
            [
                'class_name' => 'Pre-Nursery',
                'abbreviation' => 'Pre-Nursery',
                'sort' => 1,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => 'Nursery',
                'abbreviation' => 'Nursery',
                'sort' => 2,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => 'KG',
                'abbreviation' => 'KG',
                'sort' => 3,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => 'One I',
                'abbreviation' => 'One I',
                'sort' => 4,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => 'Two II',
                'abbreviation' => 'Two II',
                'sort' => 5,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => 'Three III',
                'abbreviation' => 'three III',
                'sort' => 6,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => 'Four IV',
                'abbreviation' => 'Four IV',
                'sort' => 7,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => 'Five V',
                'abbreviation' => 'five V',
                'sort' => 8,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => 'Six VI',
                'abbreviation' => 'six VI',
                'sort' => 9,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => 'Seven VII',
                'abbreviation' => 'seven VII',
                'sort' => 10,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => 'Eight VIII',
                'abbreviation' => 'eight VIII',
                'sort' => 11,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => 'Nine IX',
                'abbreviation' => 'Nine IX',
                'sort' => 12,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => '9 Matric',
                'abbreviation' => '9 Matric',
                'sort' => 13,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => '10 Matric',
                'abbreviation' => '10 Matric',
                'sort' => 14,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => '9 Cambridge',
                'abbreviation' => '9 Cambridge',
                'sort' => 15,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => '10 Cambridge',
                'abbreviation' => '10 Cambridge',
                'sort' => 16,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'class_name' => '11 Cambridge',
                'abbreviation' => '11 Cambridge',
                'sort' => 17,
                'description' => '',
                'created_at' => \Carbon\Carbon::now()
            ]
        ];

        ComClass::insert($classes);
    }
}
