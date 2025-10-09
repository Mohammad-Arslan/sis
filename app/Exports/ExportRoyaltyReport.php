<?php

namespace App\Exports;

use App\Models\StudentInvoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ExportRoyaltyReport implements FromCollection,ShouldAutoSize,WithStyles,WithHeadings
{
    private $request;
    private $total_row_count;
    public function __construct($request = null)
    {
        $this->request = $request;
        $this->total_row_count = 0;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $request = new \stdClass();
        $request->filters = $this->request->all();

        $return_data = StudentInvoice::royaltyComputation($request);
        $this->total_row_count = count($return_data['students']);

        $royalty_array = array();
        foreach($return_data['students'] as $key => $collection)
        {
            $array_data['branch_code'] = isset($collection['student']['branch']['branch_code']) ? $collection['student']['branch']['branch_code'] : '';
            $array_data['branch_name'] = isset($collection['student']['branch']['br_name']) ? $collection['student']['branch']['br_name'] : '';
            $array_data['full_name'] = isset($collection['student']) ? $collection['student']['first_name'] . ' ' . $collection['student']['middle_name'] . ' ' . $collection['student']['last_name'] : '';
            $array_data['student_id'] = $collection['student']['registration_no'] ? $collection['student']['registration_no'] : $collection['student']['roll_no'];
            $array_data['invoice_no'] = isset($collection['invoice_no']) ? $collection['invoice_no'] : '';
            $array_data['fee_month'] = get_month_diff($collection['fee_period']['from_date'], $collection['fee_period']['to_date']) == 1 ? get_month_name($collection['fee_period']['from_date']) : get_month_name($collection['fee_period']['from_date']) . ' - ' . get_month_name($collection['fee_period']['to_date']);
            $array_data['admission_wef'] = parse_date($collection['student']['admission_wef'],'d-m-Y');
            $array_data['admission_wef_month'] = parse_date($collection['student']['admission_wef'],'M-y');
            $array_data['class_name'] = '';
            if(isset($collection['student']['active_class']['branch_class_sections']['com_classes']))
                $array_data['class_name'] = $collection['student']['active_class']['branch_class_sections']['com_classes']['class_name'];
            $array_data['section_name'] = isset($collection['student_fee_package']['section']) ? $collection['student_fee_package']['section']['section_name'] : '';
            $array_data['is_paid'] = $collection['bank_payment_status'] == 'paid' ? 'Paid' : ($collection['bank_payment_status'] == 'unpaid' ? 'Unpaid' : ($collection['bank_payment_status'] == 'cancelled' ? 'Cancelled' : 'pending'));
            $array_data['paid_date'] = isset($collection['paid_date']) ? date('d-m-Y',strtotime($collection['paid_date'])) : '';

            $cost = calculate_total_price_by_invoice($collection);
            $array_data['admission_fees'] = number_format($cost['invoices_charges']['AF']);
            $array_data['tution_fees'] = number_format($cost['invoices_charges']['TF']);
            $array_data['security_fees'] = number_format($cost['invoices_charges']['SD']);
            $array_data['total_cost'] = number_format($cost['total']);
            $array_data['royalty'] = number_format($cost['royalty_amount']);
            $array_data['nwa_amount'] = number_format($cost['total_after_royalty']);

            $royalty_array[] = $array_data;

            if ($key+1 == $this->total_row_count){
                $array_data = array('','','','','','','','','','','','');
                $total_fee_charges = $return_data['total_fee_charges'];
                $array_data[12] = $total_fee_charges['admission'];
                $array_data[13] = $total_fee_charges['tution'];
                $array_data[14] = $total_fee_charges['security'];
                $array_data[15] = $total_fee_charges['total'];
                $array_data[16] = $total_fee_charges['total_royalty'];
                $array_data[17] = $total_fee_charges['nwa_amount'];

                $royalty_array[] = $array_data;
            }

        }
        return collect($royalty_array);
    }

    public function headings(): array
    {
        $csv_headers = [
            'Branch Code',
            'Branch Name',
            'Student',
            'Student ID',
            'Invoice No',
            'Fee Period',
            'Admission WEF',
            'Admission WEF Month',
            'Classes',
            'Section',
            'Payment Status',
            'Payment Date',
            'Admission Fees',
            'Tution Fees',
            'Security Deposit (Refundable)',
            'Total',
            'Royalty',
            'NWA Amount',
        ];

        return $csv_headers;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'font' => ['bold' => true,'size' => 11, 'color' => ['rgb' => 'FFFFFF'],],
                'fill' => ['fillType'   => Fill::FILL_SOLID, 'startColor' => ['rgb' => '00A78D']],
            ],
            $this->total_row_count + 2    => [
                'font' => ['bold' => true,'size' => 11],
                'fill' => ['fillType'   => Fill::FILL_SOLID, 'startColor' => ['argb' => Color::COLOR_YELLOW]],
            ],
        ];
    }
}
