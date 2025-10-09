<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * @description function to show leave type listing view
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $leaveTypes = LeaveType::all();
        return view('settings.leave_types.index', compact('leaveTypes'));
    }

    /**
     * @description function to store new leave type
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:leave_types',
           // 'no_of_days' => 'required|min:1',
            'description' => 'required'
        ]);

        LeaveType::create($request->only('name', 'description'));
        return redirect()->route('leave-types.index')->with('success', 'Leave type created successfully.');
    }

    /**
     * @description function to show single leave type edit view
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        if ($id != null) {
            $leaveType = LeaveType::find($id);
            if (!empty($leaveType)) {
                $leaveTypes = LeaveType::all();
                return view('settings.leave_types.index', compact('leaveTypes', 'leaveType'));
            }
        }
        return redirect()->route('leave-types.index');
    }

    /**
     * @description function to update leave type
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        LeaveType::where('id', $request->id)->update([
            'name' => $request->name,
            //'no_of_days' => $request->no_of_days,
            'description' => $request->description
        ]);

        return redirect()->route('leave-types.index')->with('success', 'Leave type saved successfully.');
    }

    /**
     * @description function to delete leave type
     * @param Request $request
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            LeaveType::find($request->id)->delete();
        }
    }

}
