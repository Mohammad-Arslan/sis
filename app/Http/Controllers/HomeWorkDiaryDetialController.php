<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\ClassSubject;
use Illuminate\Http\Request;
use App\Models\HomeWorkDiary;
use Illuminate\Support\Facades\DB;
use App\Models\HomeWorkDiaryDetial;
use App\Models\HomeWorkDiaryAttachment;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class HomeWorkDiaryDetialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->diary_id);
        // $request->validate([
        //     'diary_id' => 'required',
        //     'subject_id' => 'required',
        //     'homework' => 'required',
        //     'created_by' => 'required',
        //     // 'attachment' => 'required',
        //     // 'attachment.*' => 'required'
        // ]);

        if (!isset($request->diary_id) || !isset($request->subject_id) || !isset($request->homework) || !isset($request->created_by)) {
            return redirect()->route('homeWorkDiary.index')->with('error', 'Subject and Home Work fields are required, unable to insert the record.');
        }

        // if(!isset($request->attachment[0]))
        // {
        //     return redirect()->route('homeWorkDiary.index')->with('error', 'Attachment is missing.');
        // }
        $homework_detail = HomeWorkDiaryDetial::where('diary_id', $request->diary_id)->where('subject_id', $request->subject_id)->get();
        if (isset($homework_detail[0])) {
            return redirect()->route('homeWorkDiary.index')->with('error', 'Duplicate entries not allowed for same subject.');
        }
        $homework = str_replace(',', '', $request->homework);
        $homework = str_replace("'", "", $homework);
        $diary_detail_input['diary_id'] = $request->diary_id;
        $diary_detail_input['subject_id'] = $request->subject_id;
        $diary_detail_input['homework'] = $request->homework;
        $diary_detail_input['created_by'] = $request->created_by;
        //dd($diary_detail_input);
        $diary_detail = HomeWorkDiaryDetial::create($diary_detail_input);


        //$files = [];
        if ($request->hasfile('attachment')) {
            $subject = Subject::where('id', $request->subject_id)->first('subject_name');
            $i = 1;
            DB::beginTransaction();
            foreach ($request->file('attachment') as $file_index => $file) {
                $diary_attachment_input['diary_detail_id'] = $diary_detail->id;
                $filename = explode('.', $file->getClientOriginalName());
                $name = $filename[0] . '-' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $diary_attachment_input['attachment'] = $name;
                $filepath = 'images/' . $name;
                Storage::disk('s3')->put($filepath, file_get_contents($file));
                HomeWorkDiaryAttachment::create($diary_attachment_input);
                $i++;
            }
            DB::commit();
        }

        return redirect()->route('homeWorkDiary.index')->with('success', 'Home work has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HomeWorkDiaryDetial  $homeWorkDiaryDetial
     * @return \Illuminate\Http\Response
     */
    public function show(HomeWorkDiaryDetial $homeWorkDiaryDetial)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HomeWorkDiaryDetial  $homeWorkDiaryDetial
     * @return \Illuminate\Http\Response
     */
    public function edit(HomeWorkDiaryDetial $homeWorkDiaryDetial)
    {
        $class = HomeWorkDiary::where('id', $homeWorkDiaryDetial->diary_id)->first('class_id');
        $subjects = ClassSubject::where('class_id', $class->class_id)->with('subject')->get();
        return view('homeworkdiary.homework_diary_detail_edit_modal', compact('homeWorkDiaryDetial', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HomeWorkDiaryDetial  $homeWorkDiaryDetial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HomeWorkDiaryDetial $homeWorkDiaryDetial)
    {
        if (!isset($request->diary_id) || !isset($request->subject_id) || !isset($request->homework) || !isset($request->updated_by)) {
            return redirect()->route('homeWorkDiary.index')->with('error', 'Subject and Home Work fields are required, unable to insert the record.');
        }

        // if(!isset($request->attachment[0]))
        // {
        //     return redirect()->route('homeWorkDiary.index')->with('error', 'Attachment is missing.');
        // }
        $homework_detail = HomeWorkDiaryDetial::where('diary_id', $request->diary_id)->where('id', '!=', $homeWorkDiaryDetial->id)->where('subject_id', $request->subject_id)->get();
        if (isset($homework_detail[0])) {
            return redirect()->route('homeWorkDiary.index')->with('error', 'Duplicate entries not allowed for same subject.');
        }
        $homework = str_replace(',', '', $request->homework);
        $homework = str_replace("'", "", $homework);
        $diary_detail_input['diary_id'] = $request->diary_id;
        $diary_detail_input['subject_id'] = $request->subject_id;
        $diary_detail_input['homework'] = $homework;
        $diary_detail_input['updated_by'] = $request->updated_by;

        $homeWorkDiaryDetial->update($diary_detail_input);

        if ($request->hasfile('attachment')) {
            $subject = Subject::where('id', $request->subject_id)->first('subject_name');
            $i = 1;
            DB::beginTransaction();
            foreach ($request->file('attachment') as $file_index => $file) {
                $diary_attachment_input['diary_detail_id'] = $homeWorkDiaryDetial->id;
                $filename = explode('.', $file->getClientOriginalName());
                //$name = $filename[0].'-'.Str::random(5).'.'.$file->getClientOriginalExtension();
                $name = $subject->subject_name . '(' . $i . ')' . date('dmy-his') . '.' . $file->getClientOriginalExtension();
                $filename = explode('.', $file->getClientOriginalName());
                $name = $filename[0] . '-' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $diary_attachment_input['attachment'] = $name;
                $filepath = 'images/' . $name;
                Storage::disk('s3')->put($filepath, file_get_contents($file));
                HomeWorkDiaryAttachment::create($diary_attachment_input);
                $i++;
            }
            DB::commit();
        }

        //return view('homeworkdiary.homework_diary_detail_view_modal', compact('data'));

        return redirect()->route('homeWorkDiary.index')->with('success', 'Record has been updated successfully.');
    }

    public function add_homework_detail($diary_id)
    {
        if ($diary_id != null) {
            $id = $diary_id;
        }
        $class = HomeWorkDiary::where('id', $id)->first('class_id');
        $subjects = ClassSubject::where('class_id', $class->class_id)->with('subject')->get();
        //dd($subjects);
        return view('homeworkdiary.homework_diary_detail_add_modal', compact('id', 'subjects'));
    }

    public function view_homework_detail($diary_id)
    {
        $data = null;
        $homework_details = HomeWorkDiaryDetial::where('diary_id', $diary_id)->with(['subject', 'createdBy'])->get();
        //dd($homework_details->toArray());
        $i = 0;
        foreach ($homework_details as $homework_detail) {
            $data[$i]['id'] = $homework_detail->id;
            $data[$i]['diary_id'] = $homework_detail->diary_id;
            $data[$i]['subject'] = $homework_detail->subject->subject_name;
            $data[$i]['homework'] = $homework_detail->homework;
            $data[$i]['createdBy'] = $homework_detail->createdBy->name;
            $data[$i]['attachment'] = getHomeWorkDiaryAttachments($homework_detail->id, 'VIEW');
            $i++;
        }
        //dd($data);
        //$homework_detail_ids = HomeWorkDiaryDetial::where('diary_id', $diary_id)->pluck('id')->toArray();
        //$attachments =  HomeWorkDiaryAttachment::WhereIn('diary_detail_id',$homework_detail_ids)->get(['id','diary_detail_id','attachment']);

        return view('homeworkdiary.homework_diary_detail_view_modal', compact('data'));
    }

    public function delete_attachment($id)
    {
        $attachments = HomeWorkDiaryAttachment::where('id', $id)->get(['id', 'attachment']);
        if (Storage::disk('s3')->exists('images/' . $attachments[0]['attachment'])) {
            Storage::disk('s3')->delete('images/' . $attachments[0]['attachment']);
        }
        $attachments[0]->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HomeWorkDiaryDetial  $homeWorkDiaryDetial
     * @return \Illuminate\Http\Response
     */
    public function destroy(HomeWorkDiaryDetial $homeWorkDiaryDetial)
    {
        try {
            $attachments = HomeWorkDiaryAttachment::where('diary_detail_id', $homeWorkDiaryDetial->id)->get(['id', 'attachment']);
            foreach ($attachments as $attachment) {
                if (Storage::disk('s3')->exists('images/' . $attachment['attachment'])) {
                    Storage::disk('s3')->delete('images/' . $attachment['attachment']);
                    $attachment->delete();
                }
            }
            return $homeWorkDiaryDetial->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
