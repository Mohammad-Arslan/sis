<?php

namespace Database\Seeders;

use App\Models\AttendanceType;
use Illuminate\Database\Seeder;

class AttendanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendance_types = [
            [
                'name' => 'Class',
                'description' => 'Class & Section wise attendance',
                'abbreviation' => 'class',
            ],
            [
                'name' => 'Subject',
                'description' => 'Sebject wise attendance',
                'abbreviation' => 'subject',
            ]
        ];

        $dataToInsert = array();
        foreach ($attendance_types as $types)
        {
            $checkStatus = AttendanceType::where('abbreviation', $types['abbreviation'])->first();
            if(empty($checkStatus))
                $dataToInsert[] = $types;
        }

        if (sizeof($dataToInsert))
            AttendanceType::insert($dataToInsert);
    }

}
