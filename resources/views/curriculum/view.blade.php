@extends('layouts.master')
@section('content')

    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        {{-- <li class="breadcrumb-item"><a href="{{ route('lesson-plans.index') }}">Lesson Plan List</a></li> --}}
        <li class="breadcrumb-item active">View Curriculum</li>
    </x-breadcrumb>

    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">

                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('show-curriculum') }}">Curriculum</a></li>
                    @if (isset($currentClass->id))
                        <li class="breadcrumb-item">
                            <a href="{{ route('show-curriculum', ['class' => $currentClass->id]) }}">
                                {{ $currentClass->class_name }}
                            </a>
                        </li>
                    @endif
                    @if (isset($currentSubject->id))
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('show-curriculum', ['class' => $currentClass->id, 'subject' => $currentSubject->id]) }}">
                                {{ $currentSubject->subject_name }}
                            </a>
                        </li>
                    @endif
                    @if (isset($currentCurriculumType->id))
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('show-curriculum', ['class' => $currentClass->id, 'subject' => $currentSubject->id, 'type' => $currentCurriculumType->id]) }}">
                                {{ $currentCurriculumType->name }}
                            </a>
                        </li>
                    @endif
                    @if (isset($currentCurriculumCategory->id))
                        <li class="breadcrumb-item active">{{ $currentCurriculumCategory->name }}</li>
                    @endif

                </ol>
            </h4>
            <div class="flex-shrink-0">
                <a class="btn btn-sm btn-success-new print_and_download" href="javascript:void(0)">Print & Download</a>
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="row">
                <div style="background-color: #2F4050" class="col-xl-3">
                    <!-- Accordions with Icons -->
                    <div class="accordion custom-accordionwithicon1 accordion-flush" id="accordionWithicon">

                        @foreach ($groupedClassSubjects as $item)
                            <div style="background-color: #2F4050" class="accordion-item">
                                <h2 class="accordion-header" id="accordionwithiconExample{{ $item['class']['id'] }}">
                                    <button style="background-color: #2F4050; color: white" class="accordion-button"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#accor_iconExamplecollapse{{ $item['class']['id'] }}"
                                        aria-expanded="true"
                                        aria-controls="accor_iconExamplecollapse{{ $item['class']['id'] }}">
                                        <!--<i class="ri-global-line"></i>--> {{ $item['class']['class_name'] }}
                                    </button>
                                </h2>
                                <div id="accor_iconExamplecollapse{{ $item['class']['id'] }}"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="accordionwithiconExample{{ $item['class']['id'] }}"
                                    data-bs-parent="#accordionWithicon">
                                    <div class="accordion-body">

                                        <div class="col-xl-12">
                                            <ul class="list-group list-group-flush">
                                                @foreach ($item['subjects'] as $subject)
                                                    <li style="background-color: #2F4050; color: white"
                                                        style="color: green;" class="list-group-item">
                                                        {{ $subject->subject->subject_name }}
                                                        @foreach ($curriculumTypes as $type)
                                                            <ul class="list-group list-group-flush">

                                                                <li style="background-color: #2F4050"
                                                                    class="list-group-item dot">
                                                                    <a style="color: white; "
                                                                        href="{{ route('show-curriculum', ['class' => $item['class']['id'], 'subject' => $subject->subject->id, 'type' => $type->id]) }}">-
                                                                        {{ $type->name }}</a>
                                                                </li>

                                                            </ul>
                                                        @endforeach

                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>

                <div class="col-xl-3">
                    <ul class="list-group list-group-flush">
                        @if (isset($curriculumCategories))
                            @foreach ($curriculumCategories as $category)
                                <li style="background-color:#F3F3F4;"
                                    class="list-group-item @if (isset($currentCurriculumCategory->id) && $category->id == $currentCurriculumCategory->id) list-group-item-primary @endif">
                                    <a
                                        href="{{ route('show-curriculum', ['class' => $currentClass->id, 'subject' => $currentSubject->id, 'type' => $currentCurriculumType->id, 'category' => $category->id]) }}">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div class="col-xl-6">
                    @if (isset($currentCurriculumCategory))
                        @include('components.flash_message')
                        <form class="row g-3 needs-validation" novalidate method="POST"
                            action="{{ route('store-curriculum') }}">
                            @if (isset($currentCurriculumCategory->targets) && $currentCurriculumCategory->targets == 1)
                                <div class="col-12">
                                    @if (isset($attainmentTargets) && $attainmentTargets->count() > 0)
                                        <div id="target-container">
                                            @foreach ($attainmentTargets as $target)
                                                <div class="mb-3">
                                                    <label for="target_{{ $loop->iteration }}"
                                                        class="form-label">Attainment Target {{ $loop->iteration }}</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter target text" name="targets[]" id="target_1"
                                                        value="{{ $target->target }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" id="total_targets"
                                            value="{{ $attainmentTargets->count() }}" />
                                    @else
                                        <div class="mb-3" id="target-container">
                                            <label for="target_1" class="form-label">Attainment Target 1</label>
                                            <input type="text" class="form-control" placeholder="Enter target text"
                                                name="targets[]" id="target_1">
                                        </div>
                                    @endif
                                    <button type="button" id="add-target" class="btn btn-warning">Add More +</button>

                                </div>
                            @elseif(isset($curriculumDescription))
                                <div class="curriculum-description">
                                    <textarea class="form-control ckeditor-classic" rows="50" name="description">{{ $curriculumDescription }}</textarea>
                                </div>
                            @endif
                            @csrf
                            <input type="hidden" name="title" value="NULL" />
                            <input type="hidden" name="class_id" value="{{ $currentClass->id }}" />
                            <input type="hidden" name="subject_id" value="{{ $currentSubject->id }}" />
                            <input type="hidden" name="curriculum_category_id"
                                value="{{ $currentCurriculumCategory->id }}" />
                            <input type="hidden" name="type" value="{{ $currentCurriculumType->id }}" />
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit">Save Curriculum</button>
                            </div>
                        </form>
                    @else
                        {{ $curriculumDescription }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('header_scripts')
    <style type="text/css">
        @page {
            margin: 0 0 0 0;
            size: A3 portrait;
        }

        td img {
            width: 100px;
            object-fit: cover;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .card-body {
            line-height: 2.0 !important;
        }

        .card-title {
            font-size: 13px;
        }

        .accordion-body .list-group-item {
            padding: 5px;
            /* backgro/und-color:#2F4050; */
        }

        /*.nastaliq {
                font-family: 'Noto Nastaliq Urdu Draft', serif !important;
            }*/
    </style>
@endpush
@push('footer_scripts')
    <!-- ckeditor -->
    <script src="{{ asset('libs/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.print_and_download', function(e) {
                var newstr = $('.curriculum-description').html();
                var oldstr = document.body.innerHTML;
                document.body.innerHTML = newstr;
                $("body").css("size", "auto");
                $("body").css("margin", "5%");
                $("body").css("font-size", "large");
                $("body").css("background-color", "#ffffff");
                $("body").css("page-break-after", "auto");
                window.print();
                document.body.innerHTML = oldstr;
                $("body").removeAttr("style");
                return false;
            });
        });

        //If total attainment exits & more than 1
        if (document.getElementById('total_targets')) {
            let totalTargets = document.getElementById('total_targets').value;
            totalTargets = parseInt(totalTargets);
            var targetCount = totalTargets;
        } else {
            var targetCount = 1; // Initial target count
        }

        // Function to add a new input field
        function addNewTarget() {
            targetCount++;
            const newTarget = document.createElement('div');
            newTarget.classList.add('mb-3');
            newTarget.innerHTML = `
            <label for="target_${targetCount}" class="form-label">Attainment Target ${targetCount}</label>
            <input type="text" class="form-control" placeholder="Enter target text" name="targets[]" id="target_${targetCount}">
        `;
            document.getElementById('target-container').appendChild(newTarget);
        }

        // Add an event listener to the "Add More" button
        if (document.getElementById('add-target')) {
            document.getElementById('add-target').addEventListener('click', addNewTarget);
        }

        document.addEventListener("DOMContentLoaded", function() {
            var ckClassicEditor = document.querySelectorAll(".ckeditor-classic");
            ckClassicEditor.forEach(function() {
                ClassicEditor.create(document.querySelector(".ckeditor-classic")).then(function(editor) {
                    editor.ui.view.editable.element.style.height = "auto";
                }).catch(function(error) {
                    console.error(error);
                });
            });
        });
    </script>
    {{-- <script src="{{ asset('js/pages/form-editor.init.js') }}"></script> --}}
@endpush
