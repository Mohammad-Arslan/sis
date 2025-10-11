@extends('layouts.master')

@push('header_scripts')
@endpush

@section('content')
    <div class="container">
        <h2>Create Answer Dimension</h2>
        <form method="POST" action="{{ route('answer-dimensions.store') }}">
            @csrf
            <div class="form-group">
                <label for="question_dimensions_id">Question Dimension</label>
                <select class="form-control" id="question_dimensions_id" name="question_dimensions_id" required>
                    @foreach ($questionDimensions as $questionDimension)
                        <option value="{{ $questionDimension->id }}">{{ $questionDimension->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                    name="title" value="{{ old('title') }}" required>
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
