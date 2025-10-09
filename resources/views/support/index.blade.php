@extends('layouts.master')

@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active">Support</li>
    </x-breadcrumb>
    @include('components.flash_message')
    <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade description-alert hide" role="alert">
        <i class="ri-notification-off-line label-icon"></i><strong>Error</strong>
        Please enter description
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    @permission('create-support')
    @include('support.support')
    @endpermission

    @permission('list-support')
    @include('support.listing')
    @endpermission

@endsection
