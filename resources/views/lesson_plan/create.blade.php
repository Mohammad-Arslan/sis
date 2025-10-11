@extends('layouts.master')
@section('content')
    <link href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style>
        .open_editor_modal{
            cursor:pointer;
        }
    </style>
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lesson-plans.index') }}">Lesson Plan List</a></li>
        <li class="breadcrumb-item active">{{isset($lessonPlan) ? 'Edit' : 'Create'}} Lesson Plan</li>
    </x-breadcrumb>

    @include('components.flash_message')

    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{isset($lessonPlan) ? 'Edit' : 'Create'}} Lesson Plan</h4>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                @include('lesson_plan.lesson_plan')
                @include('lesson_plan.student_learning_outcomes')
                @include('lesson_plan.attachments')
            </div>
        </div>
    </div>

    @if(isset($lessonPlan))
        @include('lesson_plan.editor_modal')
    @endif
@endsection
@push('header_scripts')
    <style type="text/css">
        .ql-editor{
            line-height: 2.0 !important;
        }
        .open_editor_modal img {
            width:  100px;
            object-fit: cover;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endpush
