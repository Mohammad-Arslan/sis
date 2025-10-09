<?php

namespace Database\Seeders;

use App\Models\Relation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $relations = [
            [
                'relation_name' => 'Father',
                'created_at' => Carbon::now(),
            ],
            [
                'relation_name' => 'Mother',
                'created_at' => Carbon::now(),
            ],
            [
                'relation_name' => 'Uncle',
                'created_at' => Carbon::now(),
            ],
            [
                'relation_name' => 'Aunt',
                'created_at' => Carbon::now(),
            ],
        ];

        $dataToInsert = array();
        if (isset($relations)) {
            foreach ($relations as $relation) {
                $checkStatus = Relation::where('relation_name', $relation['relation_name'])->first();
                if (empty($checkStatus))
                    $dataToInsert[] = $relation;
            }

            if (sizeof($dataToInsert))
                Relation::insert($dataToInsert);
        }
    }
}
