@extends('layouts.master')

@section('content')
<style>
    .extreme-right {
        text-align: right;
    }
</style>
<div class="container">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="card-title">Teacher Observations</h5>
            <a href="{{ route('teacher_evaluation.create') }}" class="btn btn-success">Add Teacher</a>
        </div>
    </div>


    {{-- <div class="extreme-right">
        <a href="{{ route('teacher_evaluation.create') }}" class="btn btn-success">Add Teacher</a>
    </div> --}}
    <br>
    <div class="card">
        <table class="table table-bordered">
            <thead>
                <tr>
                    {{-- <th>ID</th> --}}
                    <th>Branch</th>
                    <th>Teacher</th>
                    <th>Evaluator</th>
                    <th>Next Observation Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($observations as $observation)
                <tr>
                    {{-- @dd($observation) --}}
                    {{-- <td>{{ $observation->id }}</td> --}}
                    <td>{{ $observation->branch->br_name }}</td>
                    <td>{{ $observation->teacher->full_name }}</td>

                    <td>{{ Auth::user()->name }}</td>
                    <td>
                        @if ($observation->observationDetails->last() && $observation->observationDetails->last()->nextobservationdate)
                            {{ $observation->observationDetails->last()->nextobservationdate }}
                        @else
                            No Next Observation
                        @endif
                    </td>


                    <td>
                        <a href="{{ route('teacher_evaluation.show', ['id' => $observation->id]) }}" class="btn btn-info">View Details</a>
                        <a href="{{ route('observation_details.create', ['id' => $observation->id]) }}" class="btn btn-primary">Create Observation</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
