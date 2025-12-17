@extends('layouts.master')

@section('content')
    <div class="row">
        @include('settings.paper_type.paper_type')
        @include('settings.paper_type.list')
    </div>
@endsection
