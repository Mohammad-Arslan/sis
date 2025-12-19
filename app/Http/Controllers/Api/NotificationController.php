<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotification(Request $request)
    {
        $notifications = SystemNotification::where([
            'class_id' => $request->class_id,
            'section_id' => $request->section_id
            ])->latest()->get();
        return response($notifications, 200);
    }
}
