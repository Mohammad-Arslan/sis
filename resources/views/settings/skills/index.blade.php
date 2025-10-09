@extends('layouts.master')

@section('content')
    <div class="row">
        @include('settings.skills.create_edit_skill')
        @include('settings.skills.list')
    </div>
@endsection
