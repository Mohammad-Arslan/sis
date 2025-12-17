<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Town;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lahore = City::where('city_name', 'Lahore')->first();

        $towns = [
            [
                'town_name' => 'Nawab Town',
                'city_id' => $lahore->id,
                'created_at' => \Carbon\Carbon::now()
            ]
        ];

        $dataToInsert = array();
        if (isset($towns)) {
            foreach ($towns as $town) {
                $checkStatus = Town::where('town_name', $town['town_name'])->first();
                if (empty($checkStatus))
                    $dataToInsert[] = $town;
            }

            if (sizeof($dataToInsert))
                Town::insert($dataToInsert);
        }
    }
}
