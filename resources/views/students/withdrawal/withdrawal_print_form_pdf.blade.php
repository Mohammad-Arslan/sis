<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Withdrawal Form</title>
    @if ($type == 'pdf')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    @endif
    <style>
        .form-container { font-family: Arial, sans-serif; }
        .form-table { border-collapse: collapse; width: 100%; }
        .form-table td, .form-table th { border: none; text-align: left; padding: 4px; }
        .form-table th { background: #1c398c; text-align: center; color: #fff; padding: 8px; }
        .form-control { border: none; width: 100%; border-bottom: 1.5px solid #1c398c !important; font-size: 12px; font-weight: bold; color: #203eac; }
        .input-label { color: #203eac; font-size: 12px; font-weight: lighter; white-space: nowrap; }
        .input-group { display: flex; align-items: flex-end; gap: 10px; width: 100%; }
        .main-title { text-align: center; }
        .main-title h1 { color: #203eac; text-transform: capitalize; font-weight: bold; margin: 0; font-size: 18px; }
        .main-title h2 { color: #203eac; text-transform: capitalize; margin: 0; font-size: 14px; }
        .w-25 { width: 25% !important; }
        .w-75 { width: 75% !important; }
        .w-100 { width: 100% !important; }
        .multi-inputs { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .logo-img { vertical-align: baseline; }
        .logo-img img { max-height: 60px; }
        .auth-text { font-size: 14px; color: #203eac; font-style: italic; }
        .prnt { text-align: center; font-size: 12px; color: #1c398c; font-weight: lighter; }
        .prnt2 { text-align: center; font-size: 12px; color: #1c398c; font-weight: lighter; }
        @media print { footer { page-break-after: always; } }
    </style>
</head>

<body class="form-container">
    @php
        // Pre-compute values to avoid repeated calculations in the template
        $studentName = '';
        $studentRollNo = '';
        $admissionDate = '';
        $className = '';
        $sectionName = '';
        $regionName = '';
        $branchName = '';
        $guardianName = '';
        $relationName = '';
        $applicationDate = '';
        $lastDayAt = '';
        $lastInvoicePaidAt = '';
        $libraryClearance = '';
        $clearanceAmount = '';
        $beneficiaryName = '';
        $beneficiaryCnic = '';
        $beneficiaryAddress = '';
        $beneficiaryPhone = '';
        $chequeNumber = '';
        $chequeDate = '';
        $securityAmount = '';
        
        if ($showFormInfo && $studentInfo) {
            $studentName = trim($studentInfo->first_name . ' ' . $studentInfo->last_name);
            $studentRollNo = $studentInfo->roll_no ?? '';
            $admissionDate = $studentInfo->admission_wef ? \Carbon\Carbon::parse($studentInfo->admission_wef)->format('d-m-Y') : '';
            
            if ($studentInfo->active_class && $studentInfo->active_class->branch_class_sections) {
                $className = $studentInfo->active_class->branch_class_sections->com_classes->class_name ?? '';
                $sectionName = $studentInfo->active_class->branch_class_sections->sections->section_name ?? '';
            }
            
            if ($studentInfo->branch) {
                $branchName = ($studentInfo->branch->branch_code ?? '') . ' - ' . ($studentInfo->branch->br_name ?? '');
            }
            
            if ($studentInfo->state) {
                $regionName = $studentInfo->state->state_name ?? '';
            }
        }
        
        if ($showFormInfo && $withdrawalInfo) {
            $applicationDate = $withdrawalInfo->application_date ? \Carbon\Carbon::parse($withdrawalInfo->application_date)->format('d-m-Y') : '';
            $lastDayAt = $withdrawalInfo->last_day_at ? \Carbon\Carbon::parse($withdrawalInfo->last_day_at)->format('d-m-Y') : '';
            $lastInvoicePaidAt = $withdrawalInfo->last_invoice_paid_at ?? '';
            $libraryClearance = isset($withdrawalInfo->library_clearance) ? ($withdrawalInfo->library_clearance == '0' ? 'NO' : 'YES') : '';
            $clearanceAmount = isset($withdrawalInfo->clearance_amount) ? ($withdrawalInfo->clearance_amount ? $withdrawalInfo->clearance_amount : 'NIL') : '';
            $beneficiaryName = $withdrawalInfo->beneficiary_name ?? '';
            $beneficiaryCnic = $withdrawalInfo->beneficiary_cnic ?? '';
            $beneficiaryAddress = $withdrawalInfo->beneficiary_postal_address ?? '';
            $beneficiaryPhone = $withdrawalInfo->beneficiary_phone ?? '';
            $chequeNumber = $withdrawalInfo->cheque_number ?? '';
            $chequeDate = $withdrawalInfo->cheque_date ? \Carbon\Carbon::parse($withdrawalInfo->cheque_date)->format('d-m-Y') : '';
            $securityAmount = $withdrawalInfo->security_amount ?? '';
        }
        
        if ($showFormInfo && $guardianInfo) {
            $guardianName = $guardianInfo->guardian_name ?? '';
            if ($guardianInfo->relation) {
                $relationName = $guardianInfo->relation->relation_name ?? '';
            }
        }
    @endphp

    <!-- Logo Section -->
    <table class="form-table logo-img">
        <tr><td><img src="{{ asset('logo-horizontal-form.png') }}" alt="UCS Schools"></td></tr>
    </table>

    <!-- Main Form -->
    <table class="form-table">
        <thead>
            <tr>
                <td colspan="2">
                    <div class="main-title">
                        <h2>united charter schools</h2>
                        <h1>withdrawal & security refund form</h1>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="multi-inputs">
                        <div class="input-group w-25">
                            <label class="input-label">Region:</label>
                            <input type="text" class="form-control" value="{{ $regionName }}">
                        </div>
                        <div class="input-group w-75">
                            <label class="input-label">Branch:</label>
                            <input type="text" class="form-control" value="{{ $branchName }}">
                        </div>
                    </div>
                </td>
            </tr>
            <tr><th colspan="2">Student Information</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="input-group">
                        <label class="input-label">Student ID:</label>
                        <input type="text" class="form-control" value="{{ $studentRollNo }}">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <label class="input-label">Student Name:</label>
                        <input type="text" class="form-control" value="{{ $studentName }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input-group">
                        <label class="input-label">Admission Date:</label>
                        <input type="text" class="form-control" value="{{ $admissionDate }}">
                    </div>
                </td>
                <td>
                    <div class="multi-inputs">
                        <div class="input-group">
                            <label class="input-label">Class:</label>
                            <input type="text" class="form-control" value="{{ $className }}">
                        </div>
                        <div class="input-group">
                            <label class="input-label">Section:</label>
                            <input type="text" class="form-control" value="{{ $sectionName }}">
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Withdrawal Information -->
    <table class="form-table" style="border: 2px solid #1c398c; margin: 10px 0;">
        <thead><tr><th colspan="2">Withdrawal Information</th></tr></thead>
        <tr>
            <td style="padding: 10px;"><p style="margin: 0; font-weight: bold; color: #203eac;">Primary</p></td>
            <td style="padding: 10px;"><p style="margin: 0; font-weight: bold; color: #203eac;">Secondary</p></td>
        </tr>
    </table>

    <!-- Applicant Information -->
    <table class="form-table">
        <tr>
            <td>
                <div class="input-group">
                    <label class="input-label">Name of Applicant:</label>
                    <input type="text" class="form-control" value="{{ $guardianName }}">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <label class="input-label">Relationship with Student:</label>
                    <input type="text" class="form-control" value="{{ $relationName }}">
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <label class="input-label">Date of Application:</label>
                    <input type="text" class="form-control" value="{{ $applicationDate }}">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <label class="input-label">Last day in School:</label>
                    <input type="text" class="form-control" value="{{ $lastDayAt }}">
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <label class="input-label">Fee Paid up till:</label>
                    <input type="text" class="form-control" value="{{ $lastInvoicePaidAt }}">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <label class="input-label">Library Clearance:</label>
                    <input type="text" class="form-control" value="{{ $libraryClearance }}">
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="input-group">
                    <label class="input-label">Clearance Amount:</label>
                    <input type="text" class="form-control" value="{{ $clearanceAmount }}">
                </div>
            </td>
        </tr>
    </table>

    <!-- Authorization -->
    <table class="form-table" style="border: 2px solid #1c398c; margin: 10px 0;">
        <thead><tr><th>Authorisation</th></tr></thead>
        <tr>
            <td style="padding: 10px;">
                <p style="margin: 0; font-size: 14px; color: #203eac;">This cross cheque for security refund, if any, can only be issued in the parents name. This will be dispatched through registered mail to the address given below.</p>
            </td>
        </tr>
    </table>

    <!-- Beneficiary Information -->
    <table class="form-table">
        <tr>
            <td>
                <div class="input-group">
                    <label class="input-label">Name:</label>
                    <input type="text" class="form-control" value="{{ $beneficiaryName }}">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <label class="input-label">Beneficiary's CNIC#:</label>
                    <input type="text" class="form-control" value="{{ $beneficiaryCnic }}">
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="input-group">
                    <label class="input-label">Postal Address:</label>
                    <input type="text" class="form-control w-100" value="{{ $beneficiaryAddress }}">
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <label class="input-label">Phone No:</label>
                    <input type="text" class="form-control" value="{{ $beneficiaryPhone }}">
                </div>
            </td>
        </tr>
    </table>

    <!-- Notice -->
    <table class="form-table">
        <tr>
            <td style="padding: 10px;">
                <p class="auth-text">I hereby understand that one month written notice of withdrawal is required for refund of the security fee, alternatively, one month's fee must be paid in lieu of such notice.</p>
            </td>
        </tr>
    </table>

    <!-- Signatures -->
    <table class="form-table">
        <tr>
            <td style="text-align: center;">
                <input type="text" class="form-control w-75" style="margin-bottom: 5px;">
                <div class="prnt2">Parent / Guardian</div>
            </td>
            <td style="text-align: center;">
                <input type="text" class="form-control w-75" style="margin-bottom: 5px;">
                <div class="prnt2">PRO</div>
            </td>
            <td style="text-align: center;">
                <input type="text" class="form-control w-75" style="margin-bottom: 5px;">
                <div class="prnt2">School Head</div>
            </td>
        </tr>
    </table>

    <!-- Refund Acknowledgement -->
    <table class="form-table" style="border: 2px solid #1c398c; margin: 10px 0;">
        <thead><tr><th colspan="3">Refund Acknowledgement</th></tr></thead>
    </table>
    <table class="form-table">
        <tr>
            <td>
                <div class="input-group">
                    <label class="input-label">Cheque No:</label>
                    <input type="text" class="form-control w-75" value="{{ $chequeNumber }}">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <label class="input-label">Dated:</label>
                    <input type="text" class="form-control w-75" value="{{ $chequeDate }}">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <label class="input-label">For Rupees:</label>
                    <input type="text" class="form-control w-75" value="{{ $securityAmount }}">
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label class="input-label">Received with thanks in full and final payment of the security deposit.</label>
            </td>
        </tr>
    </table>

    <!-- Final Signatures -->
    <table class="form-table">
        <tr>
            <td>
                <div class="input-group">
                    <label class="input-label">Signed:</label>
                    <input type="text" class="form-control w-75">
                </div>
            </td>
            <td>
                <div class="input-group">
                    <label class="input-label">Date:</label>
                    <input type="text" class="form-control w-75">
                </div>
            </td>
        </tr>
    </table>
    <div class="prnt">Parent / Guardian</div>

    <footer></footer>
    
    @if($type != 'pdf')
    <div class="hstack gap-2 justify-content-end d-print-none mt-4 p-4">
        <a href="" id="print_withdrawl_form" class="btn btn-info">
            <i class="ri-printer-line align-bottom me-1"></i> Print
        </a>
    </div>
    @endif
</body>
</html>
