<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeWorkDiary;
use App\Models\HomeWorkDiaryDetial;
use App\Models\HomeWorkDiaryAttachment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ViewHomeworkDetailApi extends Controller
{
    public function viewHomeworkDetailApi(Request $request)
    {
        try {
            // $homework_date = isset($request->homework_date) ? $request->homework_date : Carbon::now()->format('Y-m-d');
            // $homeworkDetails = HomeWorkDiaryDetial::whereHas('diary', function ($query) use ($request, $homework_date) {
            //     $query->where('class_id', $request->class_id)->whereDate('homework_date', '=', $homework_date);
            // })
            // ->with('subject')
            // ->get();

            $homework_date = isset($request->homework_date) ? $request->homework_date : Carbon::now()->format('Y-m-d');
            $homeworkDetails = HomeWorkDiaryDetial::whereHas('diary', function ($query) use ($request, $homework_date) {
                $query->where('class_id', $request->class_id)
                    ->when(isset($request->section_id), function ($query) use ($request) {
                        $query->where('section_id', $request->section_id);
                    })
                    ->whereDate('homework_date', '=', $homework_date);
            })
                ->with('subject')
                ->get();

            // Check if any data was found
            if ($homeworkDetails->isEmpty()) {
                return response()->json(['error' => 'No homework details found.'], 404);
            }

            $data = $homeworkDetails->map(function ($homeworkDetail) {
                $attachments = $this->getHomeWorkDiaryAttachments($homeworkDetail->id, 'VIEW');
                return [
                    'subject' => $homeworkDetail->subject->subject_name,
                    'homework' => $homeworkDetail->homework,
                    'attachment' => $attachments,
                    'homework_date' => $homeworkDetail->diary->homework_date->format('Y-m-d'),


                ];
            });

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            // Log any exceptions for debugging
            Log::error('Error in viewHomeworkDetail: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching homework details'], 500);
        }
    }
    public function getHomeWorkDiaryAttachments($diary_detail_id, $type)
    {
        $Files = [];
        $attachments = null;
        $i = 0;
        $attachments = HomeWorkDiaryAttachment::where('diary_detail_id', $diary_detail_id)->get(['id', 'attachment']);
        if (isset($attachments[0])) {
            foreach ($attachments as $attachment) {
                if (Storage::disk('s3')->exists('images/' . $attachment->attachment)) {
                    $Files[$i]['id'] = $attachment->id;
                    $Files[$i]['file_url'] = Storage::disk('s3')->url('images/' . $attachment->attachment);
                    $i++;
                }
            }
            return $Files;
        }
        return '';
    }
}
