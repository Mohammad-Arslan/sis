<?php

namespace Database\Seeders;

use App\Models\AttendanceStatus;
use Illuminate\Database\Seeder;

class ContactInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendance_statuses = [
            [
                'name' => 'Present',
                'abbreviation' => 'present',
                'description' => '',
            ],
            [
                'name' => 'Absent',
                'abbreviation' => 'absent',
                'description' => '',
            ],
            [
                'name' => 'Leave',
                'abbreviation' => 'leave',
                'description' => '',
            ],
            [
                'name' => 'Tardy',
                'abbreviation' => 'tardy',
                'description' => '',
            ],
            [
                'name' => 'Exempted',
                'abbreviation' => 'exempted',
                'description' => '',
            ]
        ];

        $dataToInsert = array();
        foreach ($attendance_statuses as $status) {
            $checkStatus = AttendanceStatus::where('abbreviation', $status['abbreviation'])->first();
            if (empty($checkStatus)) {
                $dataToInsert[] = $status;
            }
        }

        if (sizeof($dataToInsert)) {
            AttendanceStatus::insert($dataToInsert);
        }
    }
}
