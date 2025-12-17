<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $punjab = State::where('state_name', 'Punjab')->first();
        $sindh = State::where('state_name', 'Sindh')->first();
        $balochistan = State::where('state_name', 'Balochistan')->first();
        $kpk = State::where('state_name', 'Khyber Pakhtunkhwa')->first();

        $cities = [
            [
                'city_name' => 'Lahore',
                'abbreviation' => 'LHR',
                'state_id' => $punjab->id,
            ],
            [
                'city_name' => 'Karachi',
                'abbreviation' => 'KHR',
                'state_id' => $sindh->id,
            ],
            [
                'city_name' => 'Peshawar',
                'abbreviation' => 'PEW',
                'state_id' => $kpk->id,
            ],
            [
                'city_name' => 'Quetta',
                'abbreviation' => 'QTA',
                'state_id' => $balochistan->id,
            ],
        ];

        $dataToInsert = array();
        if (isset($cities)) {
            foreach ($cities as $city) {
                $checkStatus = City::where('city_name', $city['city_name'])->first();
                if (empty($checkStatus))
                    $dataToInsert[] = $city;
            }

            if (sizeof($dataToInsert))
                City::insert($dataToInsert);
        }
    }
}
