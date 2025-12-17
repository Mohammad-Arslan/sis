@extends('layouts.master')

@section('content')
    <div class="row">
        @include('settings.subject_marks_setup.create_edit_marks')
        @include('settings.subject_marks_setup.list')
    </div>
@endsection
