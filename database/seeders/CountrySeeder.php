<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            [
                'country_name' => 'Pakistan',
                'abbreviation' => 'PK',
                'country_code' => '92'
            ]
        ];

        DB::table('countries')->insert($countries);
    }
}
