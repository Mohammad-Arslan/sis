        @extends('layouts.master')

        @push('header_scripts')
        @endpush

        @section('content')
        <div class="container">
            <h2>Question Dimension Details</h2>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $questionDimension->title }}</h4>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('question-dimensions.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
        @endsection

        @push('footer_scripts')


        @endpush
