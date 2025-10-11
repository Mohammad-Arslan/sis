@extends('layouts.master')

@section('content')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .average-score {
            color: #318E47;
            padding: 10px;
            /* Optional: Add padding for better styling */
        }

        .star {
            color: gray;
            cursor: pointer;
            font-size: 36px;
        }

        .rated[data-rating="1"] {
            color: #B666B2;
        }

        .rated[data-rating="2"] {
            color: #FF7900;
        }

        .rated[data-rating="3"] {
            color: #FFA500;
        }

        .rated[data-rating="4"] {
            color: #318E47;
        }

        .star1 {
            color: gray;
            cursor: pointer;
            font-size: 36px;
        }

        .rated1[data-rating="1"] {
            color: #B666B2;
        }

        .rated1[data-rating="2"] {
            color: #FF7900;
        }

        .rated1[data-rating="3"] {
            color: #FFA500;
        }

        .rated1[data-rating="4"] {
            color: #318E47;
        }

        .star2 {
            color: gray;
            cursor: pointer;
            font-size: 36px;
        }

        .rated2[data-rating="1"] {
            color: #B666B2;
        }

        .rated2[data-rating="2"] {
            color: #FF7900;
        }

        .rated2[data-rating="3"] {
            color: #FFA500;
        }

        .rated2[data-rating="4"] {
            color: #318E47;
        }

        .star3 {
            color: gray;
            cursor: pointer;
            font-size: 36px;
        }

        .rated3[data-rating="1"] {
            color: #B666B2;
        }

        .rated3[data-rating="2"] {
            color: #FF7900;
        }

        .rated3[data-rating="3"] {
            color: #FFA500;
        }

        .rated3[data-rating="4"] {
            color: #318E47;
        }

        .star4 {
            color: gray;
            cursor: pointer;
            font-size: 36px;
        }

        .rated4[data-rating="1"] {
            color: #B666B2;
        }

        .rated4[data-rating="2"] {
            color: #FF7900;
        }

        .rated4[data-rating="3"] {
            color: #FFA500;
        }

        .rated4[data-rating="4"] {
            color: #318E47;
        }

        .square {
            width: 20px;
            /* Adjust the size as needed */
            height: 20px;
            /* Adjust the size as needed */

            margin: 5px;
            /* Adjust the spacing as needed */
            color: white;
            text-align: center;
            line-height: 30px;
        }

        .square-container {
            display: flex;
        }
    </style>
    <div class="container">
        <div class="card">

            <div
                style="display: flex; justify-content: space-between; align-items: center; background-color: #FFA500; color: white; border-radius: 5px;">
                <h2 style="margin: 0; padding: 10px; color:white">Competency Evaluation</h2>
                <a style="background-color: #364574; margin-right: 20px; " href="{{ route('teacher_evaluation.index') }}"
                    class="btn btn-success">Go to Dashboard</a>

            </div>

            <table class="table table-bordered">
                <tr>
                    <td>
                        <div style="display: inline;">
                            <p style="display: inline; font-weight:600;">Teacher Name: {{ $preferredName }}</p>
                            <p style="display: inline; margin-left: 10px;">({{ $createdAtDate }})</p>
                        </div>


                        </p>
                    </td>
                    <td>
                        <p style=" font-weight:600;">Subject: {{ $subjectName }}</p>

                    </td>


                </tr>
                <tr>
                    <td>
                        <p style=" font-weight:600;">Section: {{ $selectedSection }}</p>
                    </td>
                    <td>
                        <p style=" font-weight:600;">Class: {{ $selectedClass }}</p>

                    </td>
                </tr>
            </table>

            <form method="post" action="{{ route('save-dimension-ratings') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $teacherObservations->id }}">

                @foreach ($questionsdim as $questiondim)
                    <div class="card">
                        <div class="card-header" id="heading{{ $questiondim->id }}">
                            <h5 class="mb-0">
                                <div class="row">
                                    <div class="col-10">
                                        <button style="font-size: 16px; font-weight: bold; color: #E5A21B;" type="button"
                                            class="btn btn-link" onclick="toggleAccordion({{ $questiondim->id }})"
                                            aria-expanded="true" aria-controls="collapse{{ $questiondim->id }}">
                                            {{ $questiondim->title }}
                                        </button>
                                    </div>
                                    <div class="col-2">
                                        <div class="square-container">
                                            <div style="background-color: #B666B2; display: flex; align-items: center; justify-content: center;"
                                                class="square">1</div>
                                            <div style="background-color: #FF7900; display: flex; align-items: center; justify-content: center;"
                                                class="square">2</div>
                                            <div style="background-color: #FFA500; display: flex; align-items: center; justify-content: center;"
                                                class="square">3</div>
                                            <div style="background-color: #318E47; display: flex; align-items: center; justify-content: center;"
                                                class="square">4</div>
                                        </div>
                                    </div>
                                </div>
                            </h5>
                        </div>
                        <div id="collapse{{ $questiondim->id }}" class="collapse"
                            aria-labelledby="heading{{ $questiondim->id }}" data-parent="#accordion">
                            <div class="card-body">
                                <ul>
                                    <!-- Modify your HTML to include unique IDs for each sub-question -->
                                    @foreach ($questiondim->answerDimensions as $answerDimension)
                                        <li class="row" style="background-color: #EDEEEE; margin-bottom: 10px;">
                                            <div class="col-10">
                                                {{ $answerDimension->title }}
                                            </div>
                                            <div class="col-2">
                                                <div class="rating">
                                                    @for ($rating = 1; $rating <= 4; $rating++)
                                                        <input type="hidden"
                                                            name="ratings[{{ $questiondim->id }}][{{ $answerDimension->id }}][{{ $rating }}]"
                                                            value="0">
                                                        <span class="star" data-rating="{{ $rating }}"
                                                            onclick="setRating(this, {{ $questiondim->id }}, '{{ $answerDimension->id }}', {{ $rating }})">
                                                            ●
                                                        </span>
                                                    @endfor
                                                </div>
                                                {{-- <div class="answer-rating"
                                                    id="answer-rating{{ $questiondim->id }}-{{ $answerDimension->id }}">0
                                                </div> --}}
                                            </div>

                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div style="margin-left: 20px" class="content">
                                <h4 class="average-score" style="display: inline; margin-right: 10px;">Competencies Rating
                                    Average
                                    Score:</h4>
                                <p id="average-rating{{ $questiondim->id }}" style="display: inline; margin: 0;">0</p>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div style="margin-left: 20px" class="form-group">
                    <label for="nextobservationdate" class="col-md-3 col-form-label">Next Observation Date:</label>
                    <div class="col-md-4">
                        <input type="datetime-local" class="form-control" name="nextobservationdate"
                            id="nextobservationdate">

                    </div>
                </div>

                <div class="form-label-group in-border d-flex justify-content-end">
                    <button style="margin-right: 10px;" type="submit" class="btn btn-primary">Forward</button>
                </div>
            </form>






        </div>
    </div>
@endsection
@push('footer_scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleAccordion(questionId) {
            const accordion = document.getElementById(`collapse${questionId}`);
            const isOpen = accordion.classList.contains('show');
            if (!isOpen) {
                accordion.classList.add('show');
            } else {
                accordion.classList.remove('show');
            }
        }
    </script>
    <script>
        function setRating(element, questionId, answerId, rating) {
            const circles = element.parentElement.querySelectorAll('.star');
            const selectedRating = parseInt(rating);

            // Set the color and value for each circle based on the selected rating
            circles.forEach(circle => {
                const circleRating = circle.getAttribute('data-rating');
                const circleValue = parseInt(circleRating);
                if (circleValue <= selectedRating) {
                    circle.style.color = getColorForRating(circleValue);
                    // Update the hidden input value for this circle's rating
                    const inputElement = document.querySelector(
                        `input[name="ratings[${questionId}][${answerId}][${circleValue}]`);
                    if (inputElement) {
                        inputElement.value = '1';
                    }
                } else {
                    circle.style.color = '';
                    // Update the hidden input values for the remaining circles with a value of 0
                    const inputElement = document.querySelector(
                        `input[name="ratings[${questionId}][${answerId}][${circleValue}]`);
                    if (inputElement) {
                        inputElement.value = '0';
                    }
                }
            });

            // Recalculate the average rating for this answer
            calculateAnswerAverageRating(questionId, answerId);
            // Recalculate the average rating for the question
            calculateQuestionAverageRating(questionId);
        }

        function getColorForRating(rating) {
            const colors = {
                '1': '#B666B2',
                '2': '#FF7900',
                '3': '#FFA500',
                '4': '#318E47',
            };
            return colors[rating];
        }

        function calculateAnswerAverageRating(questionId, answerId) {
            const answerRatings = document.querySelectorAll(`input[name^="ratings[${questionId}][${answerId}]`);
            let totalAnswerRatings = 0;
            let totalRatingsCount = 0;

            answerRatings.forEach((input) => {
                const answerRatingValue = parseInt(input.value);
                if (!isNaN(answerRatingValue)) {
                    totalAnswerRatings += answerRatingValue;
                    totalRatingsCount++;
                }
            });

            // Calculate the average rating out of 4
            const answerAverageRating = totalRatingsCount > 0 ? (totalAnswerRatings / totalRatingsCount) * 4 : 0;

            // Update the average rating display for this answer
            const answerAverageRatingElement = document.querySelector(`#answer-rating${questionId}-${answerId}`);
            if (answerAverageRatingElement) {
                answerAverageRatingElement.textContent = answerAverageRating.toFixed(1);
            }
        }

        function calculateQuestionAverageRating(questionId) {
            const questionRatings = document.querySelectorAll(`input[name^="ratings[${questionId}]`);
            let totalQuestionRatings = 0;
            let totalRatingsCount = 0;

            questionRatings.forEach((input) => {
                const questionRatingValue = parseInt(input.value);
                if (!isNaN(questionRatingValue)) {
                    totalQuestionRatings += questionRatingValue;
                    totalRatingsCount++;
                }
            });

            // Calculate the average rating out of 4
            const questionAverageRating = totalRatingsCount > 0 ? (totalQuestionRatings / totalRatingsCount) * 4 : 0;

            // Update the average rating display for the question dimension
            const questionAverageRatingElement = document.querySelector(`#average-rating${questionId}`);
            if (questionAverageRatingElement) {
                questionAverageRatingElement.textContent = questionAverageRating.toFixed(1);
            }
        }
    </script>
@endpush
