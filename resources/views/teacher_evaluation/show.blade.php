@extends('layouts.master')

@section('content')
    <style>
        .fa-star {
            color: yellow;
        }

        .rating {
            font-weight: bold;
        }
    </style>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Observation Details</h5>
            </div>
        </div>
        <div class="card">
            @php
                $chunkedObservationDetails = $observationDetails ? $observationDetails->chunk(2) : collect();
            @endphp
            @foreach ($chunkedObservationDetails as $chunk)
                <div style="padding: 20px" class="row">
                    @foreach ($chunk as $detail)
                        <div class="col-md-6 mb-3">
                            <div class="card card-animation">
                                <div class="card-body">
                                    <h5 class="card-title">Observation Details</h5>
                                    <ul>
                                        <li>Evaluator: {{ optional(Auth::user())->name ?? 'N/A' }}</li>
                                        <li>Selected Class: {{ optional($detail->com_classes)->class_name ?? 'N/A' }}</li>
                                        <li>Selected Section: {{ optional($detail->sections)->section_name ?? 'N/A' }}</li>
                                        <li>Observation Date: {{ $detail->observation_date ?? 'N/A' }}</li>
                                        <li>Lecture Monitoring Time: {{ $detail->part_of_period_observed ?? 'N/A' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        @php
                            $displayedQuestions = [];
                        @endphp

                        <div class="col-md-6 mb-3">
                            <div class="card card-animation">
                                <div class="card-body">
                                    <ul>
                                        @if($observationDetails)
                                            @foreach ($observationDetails as $observationDetail)
                                                @if($observationDetail && $observationDetail->ratings)
                                                    @foreach ($observationDetail->ratings as $rating)
                                                        @if (
                                                            $rating &&
                                                            $rating->questionDimension &&
                                                            !in_array($rating->questionDimension->title ?? '', $displayedQuestions)
                                                        )
                                                            @php
                                                                $displayedQuestions[] = $rating->questionDimension->title ?? '';
                                                                $answerRatings = $observationDetail->ratings
                                                                    ->where('question_dimension_id', $rating->questionDimension->id ?? null)
                                                                    ->pluck('rating')
                                                                    ->toArray();
                                                                $averageRating = count($answerRatings) > 0 ? array_sum($answerRatings) / count($answerRatings) : 0;
                                                            @endphp
                                                            <li>Average Rating for {{ $rating->questionDimension->title ?? 'N/A' }}:
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= round($averageRating))
                                                                        <i class="fas fa-star"></i>
                                                                    @else
                                                                        <i class="far fa-star"></i>
                                                                    @endif
                                                                @endfor
                                                            </li>
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
        <div style="margin-right: 10px" class="form-label-group in-border d-flex justify-content-end">
            <a href="{{ route('teacher_evaluation.index') }}" class="btn btn-primary">Back to Observations</a>
        </div>
    </div>

    </div>
@endsection
<script>
    function renderStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                stars += '<i class="fas fa-star"></i>';
            } else {
                stars += '<i class="far fa-star"></i>';
            }
        }
        return stars;
    }
</script>
