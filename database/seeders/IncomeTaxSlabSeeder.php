<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IncomeTaxSlab;

class IncomeTaxSlabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing tax slabs for fiscal year 2024-25
        IncomeTaxSlab::where('fiscal_year', '2024-25')->delete();

        // Pakistani Income Tax Slabs for Fiscal Year 2024-25
        $taxSlabs = [
            [
                'fiscal_year' => '2024-25',
                'min_salary' => 0,
                'max_salary' => 600000,
                'tax_percent' => 0,
                'fixed_amount' => 0,
                'status' => 1
            ],
            [
                'fiscal_year' => '2024-25',
                'min_salary' => 600001,
                'max_salary' => 1200000,
                'tax_percent' => 2.5,
                'fixed_amount' => 0,
                'status' => 1
            ],
            [
                'fiscal_year' => '2024-25',
                'min_salary' => 1200001,
                'max_salary' => 2200000,
                'tax_percent' => 12.5,
                'fixed_amount' => 15000,
                'status' => 1
            ],
            [
                'fiscal_year' => '2024-25',
                'min_salary' => 2200001,
                'max_salary' => 3200000,
                'tax_percent' => 20,
                'fixed_amount' => 140000,
                'status' => 1
            ],
            [
                'fiscal_year' => '2024-25',
                'min_salary' => 3200001,
                'max_salary' => 4100000,
                'tax_percent' => 25,
                'fixed_amount' => 340000,
                'status' => 1
            ],
            [
                'fiscal_year' => '2024-25',
                'min_salary' => 4100001,
                'max_salary' => 5500000,
                'tax_percent' => 32.5,
                'fixed_amount' => 565000,
                'status' => 1
            ],
            [
                'fiscal_year' => '2024-25',
                'min_salary' => 5500001,
                'max_salary' => 7500000,
                'tax_percent' => 35,
                'fixed_amount' => 1015000,
                'status' => 1
            ],
            [
                'fiscal_year' => '2024-25',
                'min_salary' => 7500001,
                'max_salary' => 999999999, // Very high number for highest bracket (no practical upper limit)
                'tax_percent' => 40,
                'fixed_amount' => 1715000,
                'status' => 1
            ]
        ];

        foreach ($taxSlabs as $slab) {
            IncomeTaxSlab::create($slab);
        }

        $this->command->info('Pakistani Income Tax Slabs for FY 2024-25 have been seeded successfully!');
    }
}
