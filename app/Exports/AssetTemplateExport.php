<?php

namespace App\Exports;

use App\Models\AssetCategory;
use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AssetTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithProperties
{
    public function array(): array
    {
        // Return sample data for the template
        return [
            [
                'AST-000001',
                'Dell Latitude Laptop',
                'High-performance laptop for office use',
                'Electronics',
                'Dell Technologies',
                'SN123456789',
                'Latitude 5520',
                'Dell',
                '2024-01-15',
                '1200.00',
                '2027-01-15',
                'new',
                'active',
                'Main Branch',
                'IT Department',
                'John Doe'
            ],
            [
                'AST-000002',
                'Office Chair',
                'Ergonomic office chair',
                'Furniture',
                'Office Supplies Co',
                'CH001234',
                'ErgoMax Pro',
                'OfficeMax',
                '2024-02-01',
                '350.00',
                '2026-02-01',
                'good',
                'active',
                'Main Branch',
                'HR Department',
                'Jane Smith'
            ],
            [
                'AST-000003',
                'Projector',
                'Conference room projector',
                'Electronics',
                'Tech Solutions',
                'PRJ789012',
                'Epson PowerLite',
                'Epson',
                '2024-01-20',
                '800.00',
                '2026-01-20',
                'good',
                'active',
                'Main Branch',
                'Marketing Department',
                'Mike Johnson'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Asset Tag*',
            'Name*',
            'Description',
            'Category',
            'Supplier',
            'Serial Number',
            'Model',
            'Brand',
            'Purchase Date',
            'Purchase Price',
            'Warranty End Date',
            'Condition',
            'Status',
            'Branch',
            'Department',
            'Assigned To'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:P1')->applyFromArray([
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
        $sheet->getStyle('A2:P4')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2F2F2'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Add borders to all cells
        $sheet->getStyle('A1:P4')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Style required fields (marked with *)
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('B1')->getFont()->setBold(true);

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Asset Tag
            'B' => 25, // Name
            'C' => 30, // Description
            'D' => 15, // Category
            'E' => 20, // Supplier
            'F' => 15, // Serial Number
            'G' => 15, // Model
            'H' => 15, // Brand
            'I' => 15, // Purchase Date
            'J' => 15, // Purchase Price
            'K' => 15, // Warranty End Date
            'L' => 12, // Condition
            'M' => 12, // Status
            'N' => 15, // Branch
            'O' => 20, // Department
            'P' => 20, // Assigned To
        ];
    }

    public function properties(): array
    {
        return [
            'creator' => 'Supernova SIS',
            'lastModifiedBy' => 'Supernova SIS',
            'title' => 'Asset Import Template',
            'description' => 'Template for importing assets into the system',
            'subject' => 'Asset Management',
            'keywords' => 'asset, import, template',
            'category' => 'Asset Management',
            'manager' => 'Supernova SIS',
            'company' => 'Supernova SIS',
        ];
    }
}
