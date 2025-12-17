@extends('layouts.master')

@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active">Documents</li>
    </x-breadcrumb>
    @include('components.flash_message')
    @permission('create-attachments')
        @include('general_document.document')
    @endpermission

    @permission('list-attachments')
        @include('general_document.listing_partial')
    @endpermission

@endsection

