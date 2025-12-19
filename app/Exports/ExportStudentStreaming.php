<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Town;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use Generator;

class ExportStudentStreaming implements FromGenerator, WithHeadings, ShouldAutoSize, WithEvents
{
    private $request;
    private $academicYears = [];
    private $towns = [];
    private $countries = [];
    private $states = [];
    private $cities = [];
    private $guardians = [];
    private $addresses = [];
    private $classes = [];

    public function __construct($request = null)
    {
        $this->request = $request;
        $this->preloadAllLookupData();
    }

    /**
     * Pre-load all lookup data to avoid repeated database queries
     */
    private function preloadAllLookupData()
    {
        // Load all lookup data in memory
        $this->academicYears = AcademicYear::select('id', 'title')->pluck('title', 'id')->toArray();
        $this->towns = Town::select('id', 'town_name')->pluck('town_name', 'id')->toArray();
        $this->countries = Country::select('id', 'country_name')->pluck('country_name', 'id')->toArray();
        $this->states = State::select('id', 'state_name')->pluck('state_name', 'id')->toArray();
        $this->cities = City::select('id', 'city_name')->pluck('city_name', 'id')->toArray();

        // Pre-load all guardians data
        $this->guardians = DB::table('guardians')
            ->leftJoin('relations', 'guardians.relation_id', '=', 'relations.id')
            ->select([
                'guardians.student_id',
                'guardians.guardian_name',
                'guardians.CNIC',
                'guardians.mobile',
                'guardians.email',
                'guardians.is_parent',
                'guardians.employee_no',
                'relations.relation_name'
            ])
            ->get()
            ->keyBy('student_id')
            ->toArray();

        // Pre-load all addresses data
        $this->addresses = DB::table('student_addresses')
            ->select([
                'student_id',
                'res_phone',
                'res_sms_number',
                'res_mobile',
                'street_address',
                'per_city_id',
                'per_phone',
                'per_postal_code',
                'per_address',
                'res_country_id',
                'res_state_id',
                'res_city_id',
                'res_town_id',
                'res_postal_code',
                'res_contact_person'
            ])
            ->get()
            ->keyBy('student_id')
            ->toArray();

        // Pre-load all class data
        $this->classes = DB::table('class_students')
            ->leftJoin('branch_class_sections', 'class_students.branch_class_section_id', '=', 'branch_class_sections.id')
            ->leftJoin('com_classes', 'branch_class_sections.class_id', '=', 'com_classes.id')
            ->leftJoin('sections', 'branch_class_sections.section_id', '=', 'sections.id')
            ->where('class_students.is_valid', 1)
            ->select([
                'class_students.student_id',
                'com_classes.class_name',
                'sections.section_name'
            ])
            ->get()
            ->keyBy('student_id')
            ->toArray();
    }

    /**
     * Generate data rows using memory-efficient generator
     */
    public function generator(): Generator
    {
        $query = $this->buildQuery();

        // Process in chunks to avoid memory issues
        $query->chunk(500, function ($students) {
            foreach ($students as $student) {
                yield $this->mapStudent($student);
            }
        });
    }

    /**
     * Build the optimized query
     */
    private function buildQuery()
    {
        $query = Student::query()
            ->select([
                'students.id',
                'students.first_name',
                'students.middle_name',
                'students.last_name',
                'students.gender',
                'students.email',
                'students.date_of_birth',
                'students.admission_wef',
                'students.registration_date',
                'students.passport_number',
                'students.birth_place',
                'students.status',
                'students.transfer_status',
                'students.security_deposit',
                'students.security_amount',
                'students.security_number',
                'students.registration_fee',
                'students.test_date_time',
                'students.interview_date_time',
                'students.student_image',
                'students.cnic',
                'students.emergency_phone_number',
                'students.branch_id',
                'students.language_id',
                'students.religion_id',
                'students.nationality_id',
                'students.country_id',
                'students.state_id',
                'students.city_id',
                'students.admission_year_id',
                'students.created_at',
                'students.updated_at'
            ])
            ->leftJoin('branches', 'students.branch_id', '=', 'branches.id')
            ->leftJoin('languages', 'students.language_id', '=', 'languages.id')
            ->leftJoin('religions', 'students.religion_id', '=', 'religions.id')
            ->leftJoin('nationalities', 'students.nationality_id', '=', 'nationalities.id')
            ->leftJoin('countries', 'students.country_id', '=', 'countries.id')
            ->leftJoin('states', 'students.state_id', '=', 'states.id')
            ->leftJoin('cities', 'students.city_id', '=', 'cities.id')
            ->addSelect([
                'branches.br_name',
                'languages.language_name',
                'religions.religion_name',
                'nationalities.nationality_name',
                'countries.country_name',
                'states.state_name',
                'cities.city_name'
            ]);

        // Apply filters
        if ($this->request) {
            $this->applyFilters($query);
        }

        return $query->orderBy('students.id', 'desc');
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query)
    {
        if (auth()->user()->hasRole('network_associate')) {
            $query->where('students.branch_id', get_set_NWABranchId());
        }

        if ($this->request->academic_year_id && $this->request->academic_year_id != '') {
            $query->whereHas('class_students', function ($q) {
                $q->where('academic_year_id', $this->request->academic_year_id)
                  ->where('is_valid', 1);
            });
        }

        if ($this->request->region_id && $this->request->region_id > 0) {
            $query->whereHas('branch', function ($q) {
                $q->where('region_id', $this->request->region_id);
            });
        }

        if ($this->request->branch_id && $this->request->branch_id > 0) {
            $query->where('students.branch_id', $this->request->branch_id);
        } elseif (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $query->where('students.branch_id', get_branch_id());
        }

        if ($this->request->section_id && $this->request->section_id > 0) {
            $query->whereHas('std_fee_package', function ($q) {
                $q->where('section_id', $this->request->section_id);
            });
        }

        if ($this->request->class_id && $this->request->class_id > 0) {
            $query->whereHas('active_class.branch_class_sections', function ($q) {
                $q->where('class_id', $this->request->class_id);
            });
        }

        if ($this->request->gender && $this->request->gender != '') {
            $query->where('students.gender', $this->request->gender);
        }

        if ($this->request->status && $this->request->status != 'all') {
            if (in_array($this->request->status, ['on_roll', 'registered', 'left', 'pass-out', 'processing'])) {
                $query->where('students.status', $this->request->status);
            } elseif (in_array($this->request->status, ['transferred'])) {
                $query->where('students.from_branch', '!=', null);
            } else {
                $query->whereNull('students.status');
            }
        }

        if ($this->request->searchName && $this->request->searchName != null) {
            $query->where(function ($q) {
                $q->orWhere('students.first_name', 'like', '%' . $this->request->searchName . '%')
                  ->orWhere('students.middle_name', 'like', '%' . $this->request->searchName . '%')
                  ->orWhere('students.last_name', 'like', '%' . $this->request->searchName . '%')
                  ->orWhere('students.gender', 'like', '' . $this->request->searchName . '%')
                  ->orWhere('students.registration_no', 'like', '%' . $this->request->searchName . '%')
                  ->orWhere('students.roll_no', 'like', '%' . $this->request->searchName . '%');
            });
        }
    }

    /**
     * Safely get array value with null safety
     */
    private function safeArrayGet($array, $key, $default = '')
    {
        if (! $array || ! is_array($array) || ! isset($array[$key])) {
            return $default;
        }
        return $array[$key] ?? $default;
    }

    /**
     * Map a single student record to array
     */
    private function mapStudent($student)
    {
        $studentId = $student->id;

        // Get guardian information from preloaded data
        $guardian = $this->guardians[$studentId] ?? null;

        // Convert to array if it's an object for consistent access
        if ($guardian && is_object($guardian)) {
            $guardian = (array) $guardian;
        }
        $guardian_name = $this->safeArrayGet($guardian, 'guardian_name');
        $guardian_cnic = $this->safeArrayGet($guardian, 'CNIC');
        $guardian_mobile = $this->safeArrayGet($guardian, 'mobile');
        $guardian_email = $this->safeArrayGet($guardian, 'email');
        $relation_name = $this->safeArrayGet($guardian, 'relation_name');
        $is_parent = $this->safeArrayGet($guardian, 'is_parent', 'no');
        $employee_no = $this->safeArrayGet($guardian, 'employee_no');

        // Get address information from preloaded data
        $address = $this->addresses[$studentId] ?? null;

        // Convert to array if it's an object for consistent access
        if ($address && is_object($address)) {
            $address = (array) $address;
        }

        // Get class information from preloaded data
        $classInfo = $this->classes[$studentId] ?? null;

        // Convert to array if it's an object for consistent access
        if ($classInfo && is_object($classInfo)) {
            $classInfo = (array) $classInfo;
        }
        $class_name = $this->safeArrayGet($classInfo, 'class_name');
        $section_name = $this->safeArrayGet($classInfo, 'section_name');

        return [
            // Student Personal Information
            $student->first_name ?? '',
            $student->middle_name ?? '',
            $student->last_name ?? '',
            $student->gender ?? '',
            $student->email ?? '',
            $student->date_of_birth ? Carbon::parse($student->date_of_birth)->format('Y-m-d') : '',
            $student->admission_wef ? Carbon::parse($student->admission_wef)->format('Y-m-d') : '',
            $student->registration_date ? Carbon::parse($student->registration_date)->format('Y-m-d') : '',
            $student->passport_number ?? '',
            $student->birth_place ?? '',
            $student->status ?? '',
            $student->transfer_status ?? '',
            $student->language_name ?? '',
            $student->religion_name ?? '',
            $student->nationality_name ?? '',
            $student->country_name ?? '',
            $student->state_name ?? '',
            $student->city_name ?? '',
            $student->br_name ?? '',
            $student->security_deposit ?? '',
            $student->security_amount ?? '',
            $student->security_number ?? '',
            $this->academicYears[$student->admission_year_id] ?? '',
            $student->registration_fee ?? '',
            $student->test_date_time ? Carbon::parse($student->test_date_time)->format('Y-m-d H:i:s') : '',
            $student->interview_date_time ? Carbon::parse($student->interview_date_time)->format('Y-m-d H:i:s') : '',
            $student->student_image ?? '',
            $student->cnic ?? '',
            $student->emergency_phone_number ?? '',
            // Address fields
            $this->safeArrayGet($address, 'res_phone'),
            $this->safeArrayGet($address, 'res_sms_number'),
            $this->safeArrayGet($address, 'res_mobile'),
            $this->safeArrayGet($address, 'street_address'),
            $this->safeArrayGet($address, 'per_city_id') ? ($this->cities[$this->safeArrayGet($address, 'per_city_id')] ?? '') : '',
            $this->safeArrayGet($address, 'per_phone'),
            $this->safeArrayGet($address, 'per_postal_code'),
            $this->safeArrayGet($address, 'per_address'),
            // Guardian fields
            $guardian_name,
            $relation_name,
            $guardian_cnic,
            $guardian_mobile,
            $is_parent,
            $employee_no,
            $guardian_email,
            // Academic fields
            $this->academicYears[$student->admission_year_id] ?? '',
            $class_name,
            $section_name,
            // Additional address fields
            $this->safeArrayGet($address, 'res_country_id') ? ($this->countries[$this->safeArrayGet($address, 'res_country_id')] ?? '') : '',
            $this->safeArrayGet($address, 'res_state_id') ? ($this->states[$this->safeArrayGet($address, 'res_state_id')] ?? '') : '',
            $this->safeArrayGet($address, 'res_city_id') ? ($this->cities[$this->safeArrayGet($address, 'res_city_id')] ?? '') : '',
            $this->safeArrayGet($address, 'res_town_id') ? ($this->towns[$this->safeArrayGet($address, 'res_town_id')] ?? '') : '',
            $this->safeArrayGet($address, 'res_postal_code'),
            $this->safeArrayGet($address, 'res_contact_person'),
        ];
    }

    public function headings(): array
    {
        return [
            'first_name',
            'middle_name',
            'last_name',
            'gender',
            'email',
            'dob',
            'admission_wef',
            'registration_date',
            'passport_number',
            'birth_place',
            'status',
            'transfer_status',
            'language',
            'religion',
            'nationality',
            'country',
            'state',
            'city',
            'branch',
            'security_deposit',
            'security_amount',
            'security_number',
            'admission_year',
            'registration_fee',
            'test_date_time',
            'interview_date_time',
            'student_image',
            'cnic',
            'emergency_phone_number',
            'res_phone',
            'res_sms_number',
            'residential_mobile',
            'res_street_address',
            'per_city',
            'per_phone',
            'per_postal_code',
            'per_address',
            'guardian_name',
            'relation',
            'guardian_cnic',
            'guardian_mobile',
            'sns_employee',
            'employee_no',
            'guardian_email',
            'academic_year',
            'class',
            'section',
            'res_country',
            'res_state',
            'res_city',
            'town',
            'postal_code',
            'contact_person',
        ];
    }

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Get the highest row number
                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();

                // Style the header row
                $headerRange = 'A1:' . $highestColumn . '1';
                $event->sheet->getDelegate()->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Freeze the first row (sticky headers)
                $event->sheet->getDelegate()->freezePane('A2');

                // Add borders to all data cells
                $dataRange = 'A2:' . $highestColumn . $highestRow;
                $event->sheet->getDelegate()->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);

                // Auto-size all columns
                foreach (range('A', $highestColumn) as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
