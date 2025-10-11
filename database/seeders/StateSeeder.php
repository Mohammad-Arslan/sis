<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = Country::where('abbreviation', 'PK')->first();

        if ($country) {
            $states = [
                [
                    'state_name' => 'Punjab',
                    'country_id' => $country->id,
                ],
                [
                    'state_name' => 'Sindh',
                    'country_id' => $country->id,
                ],
                [
                    'state_name' => 'Khyber Pakhtunkhwa',
                    'country_id' => $country->id,
                ],
                [
                    'state_name' => 'Balochistan',
                    'country_id' => $country->id,
                ],
                [
                    'state_name' => 'Gilgit Baltistan',
                    'country_id' => $country->id,
                ]
            ];
        }

        $dataToInsert = array();
        if (isset($states)) {
            foreach ($states as $state) {
                $checkStatus = State::where('state_name', $state['state_name'])->first();
                if (empty($checkStatus))
                    $dataToInsert[] = $state;
            }

            if (sizeof($dataToInsert))
                State::insert($dataToInsert);
        }
    }
}
