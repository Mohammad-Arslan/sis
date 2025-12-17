@extends('layouts.master')

@push('header_scripts')
@endpush

@section('content')
    <div class="container">
        <h2>Create Dimension</h2>
        <form method="POST" action="{{ route('question-dimensions.store') }}">
            @csrf
            <div class="form-group">
                <label for="title">Dimension Name</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                    value="{{ old('title') }}" required>
                @error('title')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 10px">Create</button>
        </form>
    </div>
@endsection

@push('footer_scripts')
@endpush
