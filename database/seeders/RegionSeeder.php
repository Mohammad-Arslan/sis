<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = [
            [
                'region_name' => 'Center',
                'abbreviation' => 'center',
                'description' => 'Center Region',
            ],
            [
                'region_name' => 'North',
                'abbreviation' => 'north',
                'description' => 'North Region',
            ],
            [
                'region_name' => 'South',
                'abbreviation' => 'south',
                'description' => 'South Region',
            ]
        ];

        DB::table('regions')->insert($regions);
    }
}
