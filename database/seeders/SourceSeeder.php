<?php

namespace Database\Seeders;

use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sources = [
            [
                'source_name' => 'Social Media: Facebook/Instagram/LinkedIn',
                'created_at' => Carbon::now(),
            ],
            [
                'source_name' => 'Google/Youtube',
                'created_at' => Carbon::now(),
            ],
            [
                'source_name' => 'WhatsApp',
                'created_at' => Carbon::now(),
            ],
            [
                'source_name' => 'Billboard and Streamers',
                'created_at' => Carbon::now(),
            ],
            [
                'source_name' => 'Newspaper Advertisement',
                'created_at' => Carbon::now(),
            ],
            [
                'source_name' => 'Television Advertisement',
                'created_at' => Carbon::now(),
            ],
            [
                'source_name' => 'Friends and Family',
                'created_at' => Carbon::now(),
            ],
            [
                'source_name' => 'SMS/Voice Message',
                'created_at' => Carbon::now(),
            ],
            [
                'source_name' => 'Email',
                'created_at' => Carbon::now(),
            ],
            [
                'source_name' => 'Website',
                'created_at' => Carbon::now(),
            ],
        ];

        $dataToInsert = array();
        foreach ($sources as $status) {
            $checkStatus = Source::where('source_name', $status['source_name'])->first();
            if (empty($checkStatus)) {
                $dataToInsert[] = $status;
            }
        }

        if (sizeof($dataToInsert)) {
            Source::insert($dataToInsert);
        }
    }
}
