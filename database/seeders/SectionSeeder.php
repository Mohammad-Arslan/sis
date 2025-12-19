<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = [
            [
                'section_name' => 'Orange',
                'abbreviation' => 'orange',
                'description' => '',
            ],
            [
                'section_name' => 'Green',
                'abbreviation' => 'green',
                'description' => '',
            ],
            [
                'section_name' => 'Red',
                'abbreviation' => 'red',
                'description' => '',
            ],
        ];

        DB::table('sections')->insert($sections);
    }
}
