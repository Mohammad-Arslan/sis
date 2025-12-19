<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\SupportQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupportQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $supports = SupportQuery::with([
                'user',
                'branch',
            ]);

            $supports = $this->filteration($request, $supports);
            $supports = $this->paginateData($supports, $request);

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($supports['totalData']),
                "recordsFiltered" => intval($supports['totalFiltered']),
                "data"            => $supports['data']
            );

            return response()->json($json_data);
        }

        $data['branches'] = Branch::all();

        return view('support.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('support.support');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'file' => 'file|max:10000',
            'url' => 'max:65535',
            'priority' => 'required',
        ]);

        $input = $request->all();
        if ($request->hasfile('file')) {
            $file = $request->file;

            $file_names = SupportQuery::pluck('file_name')->unique()->toArray();
            $filename_data['filename'] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename_data['extension'] = $file->getClientOriginalExtension();
            $filename_data['filenames_arr'] = $file_names;
            $filename = getUniqueFileName($filename_data);

            $input['file_name'] = $filename;
            $filepath = 'support/' . $filename;
            Storage::disk('s3')->put($filepath, file_get_contents($file));
        }

        $input['branch_id'] = get_branch_id();
        $input['raised_by'] = auth()->user()->id;
        SupportQuery::create($input);

        return redirect()->back()->with('success', 'Your query has been registered.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupportQuery  $supportQuery
     * @return \Illuminate\Http\Response
     */
    public function show(SupportQuery $supportQuery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupportQuery  $supportQuery
     * @return \Illuminate\Http\Response
     */
    public function edit(SupportQuery $supportQuery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupportQuery  $supportQuery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupportQuery $supportQuery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupportQuery  $supportQuery
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupportQuery $supportQuery)
    {
        //
    }



    public static function filteration($request, $query)
    {

        if (isset($request->branch_id)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
                $q->orWhereNull('branch_id');
            });
        }

        if (isset($request->priority)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('priority', $request->priority);
                $q->orWhereNull('priority');
            });
        }

        if (isset($request->from_date)) {
            $query = $query->whereDate('created_at', '>=', $request->from_date);
        }

        if (isset($request->to_date)) {
            $query = $query->whereDate('created_at', '<=', $request->to_date);
        }

        return $query;
    }

    public function paginateData($query, $request, $data = 0)
    {
        $limit = $request->input('length');
        $start = $request->input('start');
        $search = $request->input('search.value');

        $totalData = $query->count();
        $query = $query->offset($start)->limit($limit);
        $totalFiltered = $totalData;

        $query = $query->orderByDesc('id')->get();

        $data = array();
        if (! empty($query)) {
            foreach ($query as $key => $support) {
                $nestedData['document_name'] = $support['file_name'] ? '<a href="' . get_file_from_s3('support/' . $support['file_name']) . '" target="_blank">' . $support['file_name'] . '</a>' : '-';
                $nestedData['url'] = $support['url'] ? '<a href="' . $support['url'] . '" target="_blank">URL</a>' : '-';
                $nestedData['branch_id'] = isset($support['branch']['branch_code']) ? $support['branch']['branch_code'] : '-';
                $nestedData['branch_name'] = isset($support['branch']['br_name']) ? $support['branch']['br_name'] : '-';
                $nestedData['raised_by'] = $support['user']['name'];
                $nestedData['date'] = date_format($support['created_at'], 'd-m-Y');
                $nestedData['description'] = $support['description'];

                if ($support['priority'] == 'low') {
                    $nestedData['priority'] = '<span class="badge bg-success">Low</span>';
                } else if ($support['priority'] == 'medium') {
                    $nestedData['priority'] = '<span class="badge bg-warning">Medium</span>';
                } else if ($support['priority'] == 'high') {
                    $nestedData['priority'] = '<span class="badge bg-danger">High</span>';
                }

                $nestedData['created_at'] = date_format($support['created_at'], 'd-m-Y');

                $data[] = $nestedData;
            }
        }

        return [
            'data' => $data,
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered,
        ];
    }
}
