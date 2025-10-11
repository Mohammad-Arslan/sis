@extends('layouts.master')

@section('content')
    @include('components.flash_message')
    <div class="row">
        @include('settings.grading_criteria.create_edit_grade')
        @include('settings.grading_criteria.list')
    </div>
@endsection
