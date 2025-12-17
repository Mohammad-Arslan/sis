@extends('layouts.master')
@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lesson-plans.index') }}">Lesson Plan List</a></li>
        <li class="breadcrumb-item active">View Lesson Plan</li>
    </x-breadcrumb>

    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">View Lesson Plan</h4>
            <div class="flex-shrink-0">
                <a class="btn btn-sm btn-success print_and_download" href="javascript:void(0)">Print & Download</a>
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            @if(isset($lessonPlan))
                @include('lesson_plan.print_pdf')
            @endif
        </div>
    </div>
@endsection
@push('header_scripts')
    <style type="text/css">
        @page {
            margin: 0 0 0 0;
            size: A3 portrait;
        }
        td img {
            width:  100px;
            object-fit: cover;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .card-body{
            line-height: 2.0 !important;
        }
        /*.nastaliq {
            font-family: 'Noto Nastaliq Urdu Draft', serif !important;
        }*/
    </style>
@endpush
@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click','.print_and_download',function(e) {
                var newstr = $('.card-body').html();
                var oldstr = document.body.innerHTML;
                document.body.innerHTML = newstr;
                $("body").css("size", "auto");
                $("body").css("margin", "5%");
                $("body").css("font-size", "large");
                $("body").css("background-color", "#ffffff");
                $("body").css("page-break-after","auto");
                window.print();
                document.body.innerHTML = oldstr;
                $("body").removeAttr("style");
                return false;
            });
        });
    </script>
@endpush
