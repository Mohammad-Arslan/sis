@extends('layouts.master')

@section('content')
    <div class="row">
        @include('settings.general_behaviour.create_edit_behaviour')
        @include('settings.general_behaviour.list')
    </div>
@endsection
