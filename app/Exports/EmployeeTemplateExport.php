<?php

namespace App\Exports;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Religion;
use App\Models\Nationality;
use App\Models\Company;
use App\Models\Region;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EmployeeTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    public function array(): array
    {
        // Sample data for the template
        return [
            [
                'Mr',
                'John',
                'Doe',
                'John Doe',
                'John Doe Sr.',
                'Jane Doe',
                'EMP001',
                'CARD123',
                'Pakistan',
                'Islam',
                '1990-05-15',
                '2025-12-31',
                'Married',
                '2020-06-10',
                2,
                1,
                'Pakistan',
                'Punjab',
                'Lahore',
                'Active',
                '2020-01-15',
                '2020-07-15',
                '2021-01-15',
                '',
                '2020-07-15',
                'No',
                '1234567890123',
                '9876543210987',
                '0300-1234567',
                'AB1234567',
                'CRB123456',
                '2020-01-01',
                'SS123456',
                '2030-01-01',
                'EMP001',
                'Beaconhouse School System',
                'Central Region',
                'Lahore Main Campus',
                'Teaching',
                'Teacher',

                '1990-05-15',
                '123 Main Street, Lahore'
            ],
            [
                'Mrs',
                'Sarah',
                'Smith',
                'Sarah Smith',
                'Robert Smith',
                'Michael Smith',
                'EMP002',
                'CARD456',
                'Pakistan',
                'Christianity',
                '1985-08-20',
                '2026-06-30',
                'Married',
                '2015-03-15',
                1,
                0,
                'Pakistan',
                'Sindh',
                'Karachi',
                'Active',
                '2015-01-10',
                '2015-07-10',
                '2016-01-10',
                '',
                '2015-07-10',
                'No',
                '2345678901234',
                '8765432109876',
                '0300-2345678',
                'CD2345678',
                'CRB234567',
                '2015-01-01',
                'SS234567',
                '2035-01-01',
                'EMP002',
                'Beaconhouse School System',
                'Southern Region',
                'Karachi Main Campus',
                'Administration',
                'Administrator',

                '1985-08-20',
                '456 Park Avenue, Karachi'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'prefix',
            'first_name',
            'last_name',
            'preferred_name',
            'father_name',
            'spouse_name',
            'pin_code',
            'card_no',
            'nationality',
            'religion',
            'date_of_birth',
            'cnic_expiry',
            'marital_status',
            'date_of_marriage',
            'no_of_children',
            'children_in_ucs',
            'country',
            'state',
            'city',
            'job_status',
            'hiring_date',
            'confirm_date',
            'regular_date',
            'left_date',
            'probation_end_date',
            'probation_extended',
            'eobi_number',
            'ni_number',
            'mobile_number',
            'passport_number',
            'crb',
            'issue_date',
            'ss_no',
            'expiry_date',
            'previous_id',
            'company',
            'region',
            'branch',
            'department',
            'designation',

            'date_of_birth',
            'address',
            'email',
            'cnic',
            'gender',
            'password'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:AV1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style the sample data rows
        $sheet->getStyle('A2:AV3')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E7E6E6'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Add borders
        $sheet->getStyle('A1:AV3')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Auto-filter
        $sheet->setAutoFilter('A1:AV1');

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // prefix
            'B' => 15,  // first_name
            'C' => 15,  // last_name
            'D' => 20,  // preferred_name
            'E' => 20,  // father_name
            'F' => 20,  // spouse_name
            'G' => 15,  // pin_code
            'H' => 15,  // card_no
            'I' => 15,  // nationality
            'J' => 15,  // religion
            'K' => 15,  // date_of_birth
            'L' => 15,  // cnic_expiry
            'M' => 15,  // marital_status
            'N' => 15,  // date_of_marriage
            'O' => 15,  // no_of_children
            'P' => 15,  // children_in_ucs
            'Q' => 15,  // country
            'R' => 15,  // state
            'S' => 15,  // city
            'T' => 12,  // job_status
            'U' => 15,  // hiring_date
            'V' => 15,  // confirm_date
            'W' => 15,  // regular_date
            'X' => 15,  // left_date
            'Y' => 15,  // probation_end_date
            'Z' => 15,  // probation_extended
            'AA' => 15, // eobi_number
            'AB' => 15, // ni_number
            'AC' => 15, // mobile_number
            'AD' => 15, // passport_number
            'AE' => 15, // crb
            'AF' => 15, // issue_date
            'AG' => 15, // ss_no
            'AH' => 15, // expiry_date
            'AI' => 15, // previous_id
            'AJ' => 25, // company
            'AK' => 20, // region
            'AL' => 25, // branch
            'AM' => 20, // department
            'AN' => 20, // designation
            'AO' => 15, // date_of_birth
            'AR' => 30, // address
            'AS' => 25, // email
            'AT' => 15, // cnic
            'AU' => 10, // gender
            'AV' => 15, // password
        ];
    }

    public function title(): string
    {
        return 'Employee Import Template';
    }
} 