@extends('layouts.master')

@push('header_scripts')
@endpush

@section('content')
    <div class="container">
        <h2>Answer Dimension Details</h2>

        <div class="card">
            <div class="card-body">
                <p class="card-text"><strong>Question Dimension:</strong> {{ $answerDimension->questionDimension->title }}
                </p>
                <h4 class="card-title">{{ $answerDimension->title }}</h4>

            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('answer-dimensions.index') }}" class="btn btn-primary">Back to List</a>
        </div>
    </div>
@endsection

@push('footer_scripts')
@endpush
