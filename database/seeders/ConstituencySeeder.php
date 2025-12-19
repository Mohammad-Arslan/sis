<?php

namespace Database\Seeders;

use App\Models\Constituency;
use App\Models\State;
use Illuminate\Database\Seeder;

class ConstituencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = State::all()->keyBy('state_name');

        $attendance_statuses = [
            [
                'name' => 'PP',
                'abbreviation' => 'pp',
                'state_id' => $states['Punjab']['id'],
            ],
            [
                'name' => 'PS',
                'abbreviation' => 'ps',
                'state_id' => $states['Sindh']['id'],
            ],
            [
                'name' => 'PK',
                'abbreviation' => 'pk',
                'state_id' => $states['Khyber Pakhtunkhwa']['id'],
            ],
            [
                'name' => 'PB',
                'abbreviation' => 'pb',
                'state_id' => $states['Balochistan']['id'],
            ],
            [
                'name' => 'NA',
                'abbreviation' => 'na',
                'state_id' => $states['Punjab']['id'],
            ]
        ];

        $dataToInsert = array();
        foreach ($attendance_statuses as $status) {
            $checkStatus = Constituency::where('abbreviation', $status['abbreviation'])->first();
            if (empty($checkStatus)) {
                $dataToInsert[] = $status;
            }
        }

        if (sizeof($dataToInsert)) {
            Constituency::insert($dataToInsert);
        }
    }
}
