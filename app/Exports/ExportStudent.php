<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Town;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ExportStudent implements FromQuery, WithHeadings, WithEvents, ShouldAutoSize, WithMapping, WithChunkReading, WithBatchInserts
{
    private $request;
    private $academicYears = null;
    private $towns = null;
    private $countries = null;
    private $states = null;
    private $cities = null;
    
    public function __construct($request = null)
    {
        $this->request = $request;
        // Pre-load lookup data to avoid repeated database queries
        $this->preloadLookupData();
    }

    /**
     * Pre-load all lookup data to avoid repeated database queries during export
     */
    private function preloadLookupData()
    {
        // Load academic years
        $this->academicYears = AcademicYear::select('id', 'title')->pluck('title', 'id')->toArray();
        
        // Load towns
        $this->towns = Town::select('id', 'town_name')->pluck('town_name', 'id')->toArray();
        
        // Load countries
        $this->countries = Country::select('id', 'country_name')->pluck('country_name', 'id')->toArray();
        
        // Load states
        $this->states = State::select('id', 'state_name')->pluck('state_name', 'id')->toArray();
        
        // Load cities
        $this->cities = City::select('id', 'city_name')->pluck('city_name', 'id')->toArray();
    }

    /**
     * @return Builder
     */
    public function query()
    {
        // Use a more efficient query with only necessary relationships and optimized joins
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
            // Use left joins instead of eager loading for better memory management
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

        // Apply filters from request
        if ($this->request) {
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
            } elseif (!isSuperAdmin() && !isHeadOfficeEmp()) {
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

        return $query->orderBy('students.id', 'desc');
    }

    /**
     * Get academic year title by ID using preloaded data
     */
    private function getAcademicYearTitle($admissionYearId)
    {
        if (!$admissionYearId) {
            return '';
        }
        
        return $this->academicYears[$admissionYearId] ?? '';
    }

    /**
     * Get town name by ID using preloaded data
     */
    private function getTownName($townId)
    {
        if (!$townId) {
            return '';
        }
        
        return $this->towns[$townId] ?? '';
    }

    /**
     * Get country name by ID using preloaded data
     */
    private function getCountryName($countryId)
    {
        if (!$countryId) {
            return '';
        }
        
        return $this->countries[$countryId] ?? '';
    }

    /**
     * Get state name by ID using preloaded data
     */
    private function getStateName($stateId)
    {
        if (!$stateId) {
            return '';
        }
        
        return $this->states[$stateId] ?? '';
    }

    /**
     * Get city name by ID using preloaded data
     */
    private function getCityName($cityId)
    {
        if (!$cityId) {
            return '';
        }
        
        return $this->cities[$cityId] ?? '';
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        // Get guardian information with optimized query
        $guardian = $this->getGuardianInfo($row->id);
        $guardian_name = $this->safeArrayGet($guardian, 'guardian_name');
        $guardian_cnic = $this->safeArrayGet($guardian, 'CNIC');
        $guardian_mobile = $this->safeArrayGet($guardian, 'mobile');
        $guardian_email = $this->safeArrayGet($guardian, 'email');
        $relation_name = $this->safeArrayGet($guardian, 'relation_name');
        $is_parent = $this->safeArrayGet($guardian, 'is_parent', 'no');
        $employee_no = $this->safeArrayGet($guardian, 'employee_no');
        
        // Get address information with optimized query
        $address = $this->getAddressInfo($row->id);
        
        // Get class and section information with optimized query
        $classInfo = $this->getClassInfo($row->id);
        $class_name = $this->safeArrayGet($classInfo, 'class_name');
        $section_name = $this->safeArrayGet($classInfo, 'section_name');
        
        return [
            // Student Personal Information (29 columns)
            $row->first_name ?? '',
            $row->middle_name ?? '',
            $row->last_name ?? '',
            $row->gender ?? '',
            $row->email ?? '',
            $row->date_of_birth ? Carbon::parse($row->date_of_birth)->format('Y-m-d') : '',
            $row->admission_wef ? Carbon::parse($row->admission_wef)->format('Y-m-d') : '',
            $row->registration_date ? Carbon::parse($row->registration_date)->format('Y-m-d') : '',
            $row->passport_number ?? '',
            $row->birth_place ?? '',
            $row->status ?? '',
            $row->transfer_status ?? '',
            $row->language_name ?? '',
            $row->religion_name ?? '',
            $row->nationality_name ?? '',
            $row->country_name ?? '',
            $row->state_name ?? '',
            $row->city_name ?? '',
            $row->br_name ?? '',
            $row->security_deposit ?? '',
            $row->security_amount ?? '',
            $row->security_number ?? '',
            $this->getAcademicYearTitle($row->admission_year_id),
            $row->registration_fee ?? '',
            $row->test_date_time ? Carbon::parse($row->test_date_time)->format('Y-m-d H:i:s') : '',
            $row->interview_date_time ? Carbon::parse($row->interview_date_time)->format('Y-m-d H:i:s') : '',
            $row->student_image ?? '',
            $row->cnic ?? '',
            $row->emergency_phone_number ?? '',
            // Address fields (in your specified order) - with null safety
            $this->safeArrayGet($address, 'res_phone'),
            $this->safeArrayGet($address, 'res_sms_number'),
            $this->safeArrayGet($address, 'res_mobile'),
            $this->safeArrayGet($address, 'street_address'),
            $this->safeArrayGet($address, 'per_city_id') ? $this->getCityName($this->safeArrayGet($address, 'per_city_id')) : '',
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
            $this->getAcademicYearTitle($row->admission_year_id),
            $class_name,
            $section_name,
            // Additional address fields (at the end as per your order) - with null safety
            $this->safeArrayGet($address, 'res_country_id') ? $this->getCountryName($this->safeArrayGet($address, 'res_country_id')) : '',
            $this->safeArrayGet($address, 'res_state_id') ? $this->getStateName($this->safeArrayGet($address, 'res_state_id')) : '',
            $this->safeArrayGet($address, 'res_city_id') ? $this->getCityName($this->safeArrayGet($address, 'res_city_id')) : '',
            $this->safeArrayGet($address, 'res_town_id') ? $this->getTownName($this->safeArrayGet($address, 'res_town_id')) : '',
            $this->safeArrayGet($address, 'res_postal_code'),
            $this->safeArrayGet($address, 'res_contact_person'),
        ];
    }

    /**
     * Safely get array value with null safety
     */
    private function safeArrayGet($array, $key, $default = '')
    {
        if (!$array || !is_array($array) || !isset($array[$key])) {
            return $default;
        }
        return $array[$key] ?? $default;
    }

    /**
     * Get guardian information for a specific student
     */
    private function getGuardianInfo($studentId)
    {
        static $guardianCache = [];
        
        if (isset($guardianCache[$studentId])) {
            return $guardianCache[$studentId];
        }
        
        $guardian = DB::table('guardians')
            ->leftJoin('relations', 'guardians.relation_id', '=', 'relations.id')
            ->where('guardians.student_id', $studentId)
            ->select([
                'guardians.guardian_name',
                'guardians.CNIC',
                'guardians.mobile',
                'guardians.email',
                'guardians.is_parent',
                'guardians.employee_no',
                'relations.relation_name'
            ])
            ->first();
        
        $guardianCache[$studentId] = $guardian ? (array) $guardian : [];
        
        return $guardianCache[$studentId];
    }

    /**
     * Get address information for a specific student
     */
    private function getAddressInfo($studentId)
    {
        static $addressCache = [];
        
        if (isset($addressCache[$studentId])) {
            return $addressCache[$studentId];
        }
        
        $address = DB::table('student_addresses')
            ->where('student_id', $studentId)
            ->select([
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
            ->first();
        
        $addressCache[$studentId] = $address ? (array) $address : [];
        
        return $addressCache[$studentId];
    }

    /**
     * Get class and section information for a specific student
     */
    private function getClassInfo($studentId)
    {
        static $classCache = [];
        
        if (isset($classCache[$studentId])) {
            return $classCache[$studentId];
        }
        
        $classInfo = DB::table('class_students')
            ->leftJoin('branch_class_sections', 'class_students.branch_class_section_id', '=', 'branch_class_sections.id')
            ->leftJoin('com_classes', 'branch_class_sections.class_id', '=', 'com_classes.id')
            ->leftJoin('sections', 'branch_class_sections.section_id', '=', 'sections.id')
            ->where('class_students.student_id', $studentId)
            ->where('class_students.is_valid', 1)
            ->select([
                'com_classes.class_name',
                'sections.section_name'
            ])
            ->first();
        
        $classCache[$studentId] = $classInfo ? (array) $classInfo : [];
        
        return $classCache[$studentId];
    }

    /**
     * Define chunk size for processing
     */
    public function chunkSize(): int
    {
        return 1000; // Process 1000 records at a time
    }

    /**
     * Define batch size for inserts
     */
    public function batchSize(): int
    {
        return 1000; // Insert 1000 records at a time
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
