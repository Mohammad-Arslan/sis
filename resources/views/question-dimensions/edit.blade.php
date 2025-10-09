@extends('layouts.master')

@push('header_scripts')
@endpush

@section('content')
    <div class="container">
        <h2>Edit Question Dimension</h2>
        <form method="POST" action="{{ route('question-dimensions.update', $questionDimension) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                    value="{{ old('title', $questionDimension->title) }}" required>
                @error('title')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 10px">Update</button>
        </form>
    </div>
@endsection

@push('footer_scripts')
@endpush
