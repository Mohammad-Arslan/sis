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
                    <p style=" font-weight:600;">Subject: {{ $subjectName}}</p>

                </td>


            </tr>
            <tr>
                <td>
                    <p style=" font-weight:600;">Section: {{ $selectedSection}}</p>
                </td>
                <td>
                    <p style=" font-weight:600;">Class: {{ $selectedClass}}</p>

                </td>
            </tr>
        </table>

        <form method="post" action="{{ route('save-dimension-ratings') }}">
            @csrf
            <input type="hidden" name="id" value="{{ $teacherObservations->id }}">

            <div id="accordion">
                <!-- Dimension 1 -->
                <div class="card">
                    <div class="card-header" id="heading1">
                        <h5 class="mb-0">
                            <div class="row">
                                <div class="col-10">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse1"
                                        aria-expanded="true" aria-controls="collapse1"
                                        style="font-size: 16px; font-weight: bold; color: #E5A21B;">
                                        1. Dimension 1 (Planning & Preparing for Thinking and Learning)
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

                    <div id="collapse1" class="collapse" aria-labelledby="heading1" data-parent="#accordion">
                        <div class="card-body">

                            <input type="hidden" name="planning_preparing_rating" id="planning_preparing_rating"
                                value="0">
                            <ul>
                                <li class="row" style="background-color: #EDEEEE;">
                                    <div class="col-10">
                                        1.1 Implements a lesson that is aimed at meeting the learning outcomes and
                                        logically
                                        leads to the key ideas by breaking down activities/skills into a series of
                                        manageable steps.
                                    </div>
                                    <div class="col-2">
                                        <div class="rating">

                                            <span class="star" data-rating="1">●</span>
                                            <span class="star" data-rating="2">●</span>
                                            <span class="star" data-rating="3">●</span>
                                            <span class="star" data-rating="4">●</span>

                                        </div>
                                    </div>
                                </li>
                                <br>
                                <li class="row" style="background-color: #EDEEEE;">
                                    <div class="col-10">
                                        1.2 Extends students’ language development by introducing students to the
                                        key
                                        vocabulary terms they will need to know and understand to successfully learn
                                        the
                                        content and apply it in their speaking and writing.
                                    </div>
                                    <div class="col-2">
                                        <div class="rating">
                                            <span class="star" data-rating="1">●</span>
                                            <span class="star" data-rating="2">●</span>
                                            <span class="star" data-rating="3">●</span>
                                            <span class="star" data-rating="4">●</span>

                                        </div>
                                    </div>
                                </li>
                                <br>
                                <li class="row" style="background-color: #EDEEEE;">
                                    <div class="col-10">
                                        1.3 Organizes a lesson that emphasizes students doing and thinking over
                                        passively
                                        watching and listening to the teacher by using “hooks” (thought provoking
                                        activities
                                        or questions, which probe, extend and clarify student responses and that
                                        capture
                                        student interest and activate their prior knowledge).
                                    </div>
                                    <div class="col-2">
                                        <div class="rating">
                                            <span class="star" data-rating="1">●</span>
                                            <span class="star" data-rating="2">●</span>
                                            <span class="star" data-rating="3">●</span>
                                            <span class="star" data-rating="4">●</span>

                                        </div>
                                    </div>
                                </li>
                                <br>
                                <li class="row" style="background-color: #EDEEEE;">
                                    <div class="col-10">
                                        1.4 Incorporates attributes of BSS learner profile in the lesson by engaging
                                        students in extended, higher-order thinking challenges, through discussion,
                                        dialogue, debate and ineteraction (e.g., inquiry, investigation,
                                        problem-based
                                        learning, projects etc) .
                                    </div>
                                    <div class="col-2">
                                        <div class="rating">
                                            <span class="star" data-rating="1">●</span>
                                            <span class="star" data-rating="2">●</span>
                                            <span class="star" data-rating="3">●</span>
                                            <span class="star" data-rating="4">●</span>

                                        </div>
                                    </div>
                                </li>
                                <br>
                                <li class="row" style="background-color: #EDEEEE;">
                                    <div class="col-10">
                                        1.5 Uses technology (where available) as a tool to allow students the
                                        opportunities
                                        to share information and communicate effectively through a process of
                                        review,
                                        adapt,
                                        revise and evaluate work, as it progresses.
                                    </div>
                                    <div class="col-2">
                                        <div class="rating">
                                            <span class="star" data-rating="1">●</span>
                                            <span class="star" data-rating="2">●</span>
                                            <span class="star" data-rating="3">●</span>
                                            <span class="star" data-rating="4">●</span>

                                        </div>
                                    </div>
                                </li>
                            </ul>

                        </div>
                        <div style="margin-left: 20px" class="content">
                            <h4 class="average-score" style="display: inline; margin-right: 10px;">Competencies
                                Rating
                                Average Score:</h4>
                            <p id="average-rating" style="display: inline; margin: 0;">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="heading2">
                    <h5 class="mb-0">
                        <div class="row">
                            <div class="col-10">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse2"
                                    aria-expanded="true" aria-controls="collapse2"
                                    style="font-size: 16px; font-weight: bold; color: #E5A21B;">
                                    2. Dimension 2 (Maintaining a Positive Relationship & Climate)
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

                <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordion">
                    <div class="card-body">

                        <input type="hidden" name="promoting_interest_rating" id="promoting_interest_rating" value="0">
                        <ul>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    2.1 Creates a positive classroom environment , by maintaining a passion for
                                    teaching,
                                    learning, and quality work throughout the lesson, whilst ensuring students
                                    interests,
                                    aspirations, and backgrounds are catered for.
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star1" data-rating="1">●</span>
                                        <span class="star1" data-rating="2">●</span>
                                        <span class="star1" data-rating="3">●</span>
                                        <span class="star1" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    2.2 Corrects student errors/misunderstandings in positive ways that reflect
                                    patience,
                                    caring
                                    and confidence and interacts respectfully with students (e.g., listens attentively,
                                    acknowledges comments, makes eye contact).
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star1" data-rating="1">●</span>
                                        <span class="star1" data-rating="2">●</span>
                                        <span class="star1" data-rating="3">●</span>
                                        <span class="star1" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    2.3 Builds a classroom community through fairness, courtesy, and consideration, that
                                    insists
                                    on mutual support for each student’s learning, whilst providing opportunities for
                                    students
                                    to become familiar with each other.
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star1" data-rating="1">●</span>
                                        <span class="star1" data-rating="2">●</span>
                                        <span class="star1" data-rating="3">●</span>
                                        <span class="star1" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    2.4 Builds confidence so that students are comfortable seeking support from teacher
                                    or
                                    peers
                                    when needed and allowing them opportunity to express their views.
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star1" data-rating="1">●</span>
                                        <span class="star1" data-rating="2">●</span>
                                        <span class="star1" data-rating="3">●</span>
                                        <span class="star1" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    2.5 Uses consistent routines, transitions and cues to minimize time required for
                                    routine
                                    tasks (e.g., collecting homework assignments).
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star1" data-rating="1">●</span>
                                        <span class="star1" data-rating="2">●</span>
                                        <span class="star1" data-rating="3">●</span>
                                        <span class="star1" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div style="margin-left: 20px" class="content">
                        <h4 class="average-score" style="display: inline; margin-right: 10px;">Competencies Rating
                            Average
                            Score:</h4>
                        <p id="average-rating1" style="display: inline; margin: 0;">0</p>
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-header" id="heading3">
                    <h5 class="mb-0">
                        <div class="row">
                            <div class="col-10">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse3"
                                    aria-expanded="true" aria-controls="collapse3"
                                    style="font-size: 16px; font-weight: bold; color: #E5A21B;">
                                    3. Dimension 3 (Promoting Interest and Presenting New Learning)
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

                <div id="collapse3" class="collapse" aria-labelledby="heading2" data-parent="#accordion">
                    <div class="card-body">

                        <input type="hidden" name="maintaining_relation_rating" id="maintaining_relation_rating"
                            value="0">
                        <ul>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    3.1 Designs lessons and units that captures students’ interest by linking the
                                    learning
                                    to
                                    their lives and/or to real life.
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star2" data-rating="1">●</span>
                                        <span class="star2" data-rating="2">●</span>
                                        <span class="star2" data-rating="3">●</span>
                                        <span class="star2" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    3.2 Incorporates multiple sources of information, including multimedia resources,
                                    and
                                    outside resources (e.g. field trips, guest speakers, interactive technology, from
                                    community,
                                    where applicable) into lessons to help students acquire new knowledge..
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star2" data-rating="1">●</span>
                                        <span class="star2" data-rating="2">●</span>
                                        <span class="star2" data-rating="3">●</span>
                                        <span class="star2" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    3.3 Demonstrates high-quality communication skills (e.g., expressive language, rich
                                    vocabulary, proper use) for instruction, feedback and praise.
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star2" data-rating="1">●</span>
                                        <span class="star2" data-rating="2">●</span>
                                        <span class="star2" data-rating="3">●</span>
                                        <span class="star2" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    3.4 Uses a variety of presentation techniques (e.g., visuals, drama, stories, use of
                                    imagery, etc.) to make lessons vivid and encourage active student engagement..
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star2" data-rating="1">●</span>
                                        <span class="star2" data-rating="2">●</span>
                                        <span class="star2" data-rating="3">●</span>
                                        <span class="star2" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    3.5 Uses a variety of techniques (e.g. signaling, surveying, whiteboard-response
                                    systems,
                                    Think-PairShare, provisional writing, wait time, progressive questioning) to check
                                    for
                                    understanding and promote different levels of thinking..
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star2" data-rating="1">●</span>
                                        <span class="star2" data-rating="2">●</span>
                                        <span class="star2" data-rating="3">●</span>
                                        <span class="star2" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div style="margin-left: 20px" class="content">
                        <h4 class="average-score" style="display: inline; margin-right: 10px;">Competencies Rating
                            Average
                            Score:</h4>
                        <p id="average-rating2" style="display: inline; margin: 0;">0</p>
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-header" id="heading3">
                    <h5 class="mb-0">
                        <div class="row">
                            <div class="col-10">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse4"
                                    aria-expanded="true" aria-controls="collapse4"
                                    style="font-size: 16px; font-weight: bold; color: #E5A21B;">
                                    4. Dimension 4 (Assessment and Application of Learning)
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

                <div id="collapse4" class="collapse" aria-labelledby="heading3" data-parent="#accordion">
                    <div class="card-body">
                        <input type="hidden" name="assessment_application_rating" id="assessment_application_rating"
                            value="0">

                        <ul>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    4.1 Probes, extends, and clarifies student responses using effective questioning and
                                    response techniques to check for understanding in real time..
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star3" data-rating="1">●</span>
                                        <span class="star3" data-rating="2">●</span>
                                        <span class="star3" data-rating="3">●</span>
                                        <span class="star3" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    4.2 Identifies critical stages in the learning by establishing targets that students
                                    must
                                    achieve at each stage.
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star3" data-rating="1">●</span>
                                        <span class="star3" data-rating="2">●</span>
                                        <span class="star3" data-rating="3">●</span>
                                        <span class="star3" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    4.3 Uses a variety of formative assessment activities to help students assess their
                                    progress
                                    towards the targets.
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star3" data-rating="1">●</span>
                                        <span class="star3" data-rating="2">●</span>
                                        <span class="star3" data-rating="3">●</span>
                                        <span class="star3" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    4.4 Designs culminating assessments (when applicable) that require students to
                                    transfer
                                    their learning in meaningful ways. (e.g. research projects that capture student
                                    interest).
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star3" data-rating="1">●</span>
                                        <span class="star3" data-rating="2">●</span>
                                        <span class="star3" data-rating="3">●</span>
                                        <span class="star3" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    4.5 Helps students reflect on their own and their peers’ learning process to
                                    identify
                                    what
                                    they did well and where they’d like to improve. (e.g., providing models of
                                    high-quality
                                    work, rubrics, checklists, etc.).
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star3" data-rating="1">●</span>
                                        <span class="star3" data-rating="2">●</span>
                                        <span class="star3" data-rating="3">●</span>
                                        <span class="star3" data-rating="4">●</span>

                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div style="margin-left: 20px" class="content">
                        <h4 class="average-score" style="display: inline; margin-right: 10px;">Competencies Rating
                            Average
                            Score:</h4>
                        <p id="average-rating3" style="display: inline; margin: 0;">0</p>
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-header" id="heading4">
                    <h5 class="mb-0">
                        <div class="row">
                            <div class="col-10">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse5"
                                    aria-expanded="true" aria-controls="collapse5"
                                    style="font-size: 16px; font-weight: bold; color: #E5A21B;">
                                    5. Dimension 5 (catering for diversity)
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

                <div id="collapse5" class="collapse" aria-labelledby="heading4" data-parent="#accordion">
                    <div class="card-body">
                        <input type="hidden" name="catering_diversity_rating" id="catering_diversity_rating" value="0">

                        <ul>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    5.1 Scaffolds learning activities to address different students’ needs and levels
                                    (e.g.,
                                    providing reminders, breaking a problem into steps, providing examples) and to
                                    promote
                                    students’ independence as leaners
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star4" data-rating="1">●</span>
                                        <span class="star4" data-rating="2">●</span>
                                        <span class="star4" data-rating="3">●</span>
                                        <span class="star4" data-rating="4">●</span>
                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    5.2 Accommodates varied student time-needs by providing relevant and meaningful
                                    extension
                                    tasks for students who complete work early.
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star4" data-rating="1">●</span>
                                        <span class="star4" data-rating="2">●</span>
                                        <span class="star4" data-rating="3">●</span>
                                        <span class="star4" data-rating="4">●</span>
                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    5.3 Incorporates elements of students’ cultural/community backgrounds into
                                    instruction.
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star4" data-rating="1">●</span>
                                        <span class="star4" data-rating="2">●</span>
                                        <span class="star4" data-rating="3">●</span>
                                        <span class="star4" data-rating="4">●</span>
                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    5.4 Adopts and adapts a variety of strategies to make ideas clear and accessible to
                                    all
                                    students; uses auditory, visual and kinesthetic modalities when presenting material
                                    to
                                    the
                                    class.
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star4" data-rating="1">●</span>
                                        <span class="star4" data-rating="2">●</span>
                                        <span class="star4" data-rating="3">●</span>
                                        <span class="star4" data-rating="4">●</span>
                                    </div>
                                </div>
                            </li>
                            <br>
                            <li class="row" style="background-color: #EDEEEE;">
                                <div class="col-10">
                                    5.5 Aligns summative assessments with learning outcomes and targets..
                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        <span class="star4" data-rating="1">●</span>
                                        <span class="star4" data-rating="2">●</span>
                                        <span class="star4" data-rating="3">●</span>
                                        <span class="star4" data-rating="4">●</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div style="margin-left: 20px" class="content">
                        <h4 class="average-score" style="display: inline; margin-right: 10px;">Competencies Rating
                            Average
                            Score:</h4>
                        <p id="average-rating4" style="display: inline; margin: 0;">0</p>
                    </div>
                </div>

                <div style="margin-left: 20px" class="form-group">
                    <label for="nextobservationdate" class="col-md-3 col-form-label">Next Observation Date:</label>
                    <div class="col-md-4">
                        <input type="datetime-local" class="form-control" name="nextobservationdate"
                            id="nextobservationdate">

                    </div>
                </div>
                <br>

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
    // Prevent the accordion from automatically closing
    $(document).ready(function () {
        $('#heading1 button').on('click', function (e) {
            e.preventDefault(); // Prevent the default behavior (closing)
        });

        // Handle star rating click events
        $('.star').on('click', function () {
            const rating = parseInt($(this).data('rating'));
            const parentLi = $(this).parent();
            parentLi.children('.star').removeClass('rated');
            parentLi.children('.star').each(function (index) {
                if (index < rating) {
                    $(this).addClass('rated');
                }
            });

            // Calculate and display the average rating
            const totalStars = parentLi.children('.star.rated').length;
            const totalPoints = parentLi.children('.star').length;
            const averageRating = totalStars / totalPoints * 4;
            updateAverageRating();

            // Set the value of the hidden input field
            $('#planning_preparing_rating').val(averageRating.toFixed(1));
        });

        // Function to update and display the average rating
        function updateAverageRating() {
            const allRatedStars = $('.star.rated');
            const totalStars = allRatedStars.length;
            const totalPoints = $('.star').length;
            const averageRating = (totalStars / totalPoints) * 4;
            $('#average-rating').text(averageRating.toFixed(1));
        }
    });
</script>




<script>
    // Prevent the accordion from automatically closing
    $(document).ready(function () {
        $('#heading2 button').on('click', function (e) {
            e.preventDefault();
        });

        // Handle star rating click events
        $('.star1').on('click', function () {
            const rating = parseInt($(this).data('rating'));
            const parentLi = $(this).parent();
            parentLi.children('.star1').removeClass('rated1');
            parentLi.children('.star1').each(function (index) {
                if (index < rating) {
                    $(this).addClass('rated1');
                }
            });

            // Calculate and display the average rating
            const totalStars = parentLi.children('.star1.rated1').length;
            const totalPoints = parentLi.children('.star1').length;
            const averageRating = totalStars / totalPoints * 4;
            updateAverageRating();

            console.log('Average Rating:', averageRating);
            $('#promoting_interest_rating').val(averageRating.toFixed(1));


        });

        // Function to update and display the average rating
        function updateAverageRating() {
            const allRatedStars = $('.star1.rated1');
            const totalStars = allRatedStars.length;
            const totalPoints = $('.star1').length;
            const averageRating = (totalStars / totalPoints) * 4;
            $('#average-rating1').text(averageRating.toFixed(1));
        }
    });
</script>



<script>
    // Prevent the accordion from automatically closing
    $(document).ready(function () {
        $('#heading3 button').on('click', function (e) {
            e.preventDefault();
        });

        // Handle star rating click events
        $('.star2').on('click', function () {
            const rating = parseInt($(this).data('rating'));
            const parentLi = $(this).parent();
            parentLi.children('.star2').removeClass('rated2');
            parentLi.children('.star2').each(function (index) {
                if (index < rating) {
                    $(this).addClass('rated2');
                }
            });

            // Calculate and display the average rating
            const totalStars = parentLi.children('.star2.rated2').length;
            const totalPoints = parentLi.children('.star2').length;
            const averageRating = totalStars / totalPoints * 4;
            updateAverageRating();

            $('#maintaining_relation_rating').val(averageRating.toFixed(1));

        });

        // Function to update and display the average rating
        function updateAverageRating() {
            const allRatedStars = $('.star2.rated2');
            const totalStars = allRatedStars.length;
            const totalPoints = $('.star2').length;
            const averageRating = (totalStars / totalPoints) * 4;
            $('#average-rating2').text(averageRating.toFixed(1));
        }
    });
</script>


<script>
    // Prevent the accordion from automatically closing
    $(document).ready(function () {
        $('#heading4 button').on('click', function (e) {
            e.preventDefault();
        });

        // Handle star rating click events
        $('.star3').on('click', function () {
            const rating = parseInt($(this).data('rating'));
            const parentLi = $(this).parent();
            parentLi.children('.star3').removeClass('rated3');
            parentLi.children('.star3').each(function (index) {
                if (index < rating) {
                    $(this).addClass('rated3');
                }
            });

            // Calculate and display the average rating
            const totalStars = parentLi.children('.star3.rated3').length;
            const totalPoints = parentLi.children('.star3').length;
            const averageRating = totalStars / totalPoints * 4;
            updateAverageRating();

            $('#assessment_application_rating').val(averageRating.toFixed(1));

        });

        // Function to update and display the average rating
        function updateAverageRating() {
            const allRatedStars = $('.star3.rated3');
            const totalStars = allRatedStars.length;
            const totalPoints = $('.star3').length;
            const averageRating = (totalStars / totalPoints) * 4;
            $('#average-rating3').text(averageRating.toFixed(1));
        }
    });
</script>


<script>
    // Prevent the accordion from automatically closing
    $(document).ready(function () {
        $('#heading5 button').on('click', function (e) {
            e.preventDefault();
        });

        // Handle star rating click events
        $('.star4').on('click', function () {
            const rating = parseInt($(this).data('rating'));
            const parentLi = $(this).parent();
            parentLi.children('.star4').removeClass('rated4');
            parentLi.children('.star4').each(function (index) {
                if (index < rating) {
                    $(this).addClass('rated4');
                }
            });

            // Calculate and display the average rating
            const totalStars = parentLi.children('.star4.rated4').length;
            const totalPoints = parentLi.children('.star4').length;
            const averageRating = totalStars / totalPoints * 4;
            updateAverageRating();
            $('#catering_diversity_rating').val(averageRating.toFixed(1));


        });

        // Function to update and display the average rating
        function updateAverageRating() {
            const allRatedStars = $('.star4.rated4');
            const totalStars = allRatedStars.length;
            const totalPoints = $('.star4').length;
            const averageRating = (totalStars / totalPoints) * 4;
            $('#average-rating4').text(averageRating.toFixed(1));
        }
    });
</script>
@endpush
