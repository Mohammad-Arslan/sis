<?php

namespace Database\Seeders;

use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataToInsert = array();
        for ($i = 1; $i <= 30; $i++) {
            $checkStatus = Week::find($i);
            if (empty($checkStatus)) {
                $dataToInsert[] = [
                    'name' => 'Week ' . $i,
                    'description' => 'Week ' . $i,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        if (sizeof($dataToInsert)) {
            Week::insert($dataToInsert);
        }
    }
}
