<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::with(['branch'])->where('company_name','Super Nova')->first();

        $dataToInsert = array();

        if (isset($company['branch']))
        {
            $checkStatus = Term::where([['company_id',$company['id'],['branch_id',$company['branch']['id']]]])->first();
            if(empty($checkStatus)){
                $dataToInsert[] = [
                    'branch_id' => $company['id'],
                    'company_id' => $company['branch']['id'],
                    'name' => 'Term 1',
                    'description' => 'Term 1',
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addYear(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        if (sizeof($dataToInsert))
            Term::insert($dataToInsert);
    }
}
