<?php

namespace Database\Seeders;

use App\Models\InvoiceType;
use App\Models\Relation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InvoiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invoice_types = [
            [
                'name' => 'Receivable',
                'description' => 'Receivable',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Payable',
                'description' => 'Payable',
                'created_at' => Carbon::now(),
            ],
        ];

        $dataToInsert = array();
        if (isset($invoice_types)) {
            foreach ($invoice_types as $invoice_type) {
                $checkStatus = InvoiceType::where('name', $invoice_type['name'])->first();
                if (empty($checkStatus))
                    $dataToInsert[] = $invoice_type;
            }

            if (sizeof($dataToInsert))
                InvoiceType::insert($dataToInsert);
        }
    }
}
