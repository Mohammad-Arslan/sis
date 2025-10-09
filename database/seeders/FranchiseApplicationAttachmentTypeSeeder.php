<?php

namespace Database\Seeders;

use App\Models\FranchiseApplicationAttachmentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FranchiseApplicationAttachmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Bank Statement for the last 6 months'],
            ['name' => 'Copy of Board Resolution/Authority Letter'],
            ['name' => 'Copy of Ownership/Rental/Lease'],
            ['name' => 'Copy of Computerised CNIC (Front)'],
            ['name' => 'Copy of Computerised CNIC (Back)'],
            ['name' => 'Bank Draft of Rs.5000'],
            ['name' => 'Letter of Intent (LOI)'],
            ['name' => 'Memorandum of understanding (MOU)'],
            ['name' => 'Franchise Agreement (FA)'],
            ['name' => 'Updated CV'],
            ['name' => 'Business Card'],
        ];

        foreach ($types as $type){
            $type_availability = FranchiseApplicationAttachmentType::where('name',$type)->first();
            if (!$type_availability)
                FranchiseApplicationAttachmentType::create($type);
        }
    }
}
