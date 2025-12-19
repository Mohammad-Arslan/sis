<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralDocument extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'attachment_type_id',
        'branch_id',
        'file_name',
        'document_name',
        'remarks',
        'uploaded_by',
        'academic_year_id',
        'com_class_id',
        'subject_id',
        'state_id',
        'status',
        'document_type',
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function attachment_type()
    {
        return $this->belongsTo(AttachmentType::class, 'attachment_type_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function com_class()
    {
        return $this->belongsTo(ComClass::class, 'com_class_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public static function filterationDropdownData()
    {

        $user = auth()->user();

        $data['academic_years'] = AcademicYear::all();

        //branch filter dropdown
        if (auth()->user()->hasRole('network_associate')) {
            $data['branches'] = Branch::whereIn('id', auth()->user()['networkAssociates']['branches']->pluck('id')->toArray())->get();
        } elseif (! auth()->user()->hasRole('super_admin') && auth()->user()->hasPermission('add-lessonplan-taughtdate')) { //assuming, The user who has this permission would be TEACHER
            $data['branches'] = Branch::where('id', auth()->user()['employee']['branch_id'])->get();
        } else {
            $data['branches'] = Branch::all();
        }

        //classes filter dropdown
        if (auth()->user()->hasRole('super_admin')) {
            $data['classes'] = ComClass::all();
        } else if (auth()->user()->hasRole('network_associate')) {
            $data['classes'] = ComClass::whereIn('id', get_branch_class_ids(get_set_NWABranchId()))->get();
        } else if (get_teacher_classes()->isNotEmpty()) {
            $data['classes'] = get_teacher_classes();
        } else {
            $data['classes'] = ComClass::whereIn('id', get_branch_class_ids($user['employee']['branch_id']))->get();
        }

        $data['subjects'] = get_teacher_subjects()->isNotEmpty() ? get_teacher_subjects() : Subject::all();

        //province filter dropdown
        if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $data['states'] = State::where('id', get_branch_state_id())->get();
        } else {
            $data['states'] = State::all();
        }

        return $data;
    }

    public static function filteration($request, $query)
    {
        //branch filteration
        if (! auth()->user()->hasRole('super_admin') && ! auth()->user()->hasPermission('approve-lesson-plan') && ! auth()->user()->hasRole('subject_coordinator')) {
            $query = $query->where(function ($q) {
                $q->whereNull('branch_id')->orWhere('branch_id', get_branch_id());
            });
        }

        //province filteration
        if (! auth()->user()->hasRole('super_admin') && ! auth()->user()->hasPermission('approve-lesson-plan') && ! auth()->user()->hasRole('subject_coordinator')) {
            $state_id = get_branch_state_id();
            $query = $query->where(function ($query1) use ($request, $state_id) {
                $query1->where('state_id', $state_id);
                $query1->orWhereNull('state_id');
            });
        }

        //classes filteration
        if (! auth()->user()->hasRole('subject_coordinator') && get_teacher_classes()->isNotEmpty()) {
            $query = $query->where(function ($query1) use ($request, $state_id) {
                $query1->whereIn('com_class_id', get_teacher_classes()->pluck('id')->toArray());
                $query1->orWhereNull('com_class_id');
            });
        }

        if (isset($request->academic_year_id)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
                $q->orWhereNull('academic_year_id');
            });
        }

        if (isset($request->branch_id)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
                $q->orWhereNull('branch_id');
            });
        }

        if (isset($request->attachment_type_id)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('attachment_type_id', $request->attachment_type_id);
                $q->orWhereNull('attachment_type_id');
            });
        }
        if (isset($request->com_class_id)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('com_class_id', $request->com_class_id);
                $q->orWhereNull('com_class_id');
            });
        }
        if (isset($request->state_id)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('state_id', $request->state_id);
                $q->orWhereNull('state_id');
            });
        }
        if (isset($request->subject_id)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
                $q->orWhereNull('subject_id');
            });
        }

        return $query;
    }
}
