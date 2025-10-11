@extends('layouts.master')

@section('content')
    <x-breadcrumb>
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
        @if(isset($studentBehaviourSkill))
            <li class="breadcrumb-item {{isset($studentBehaviourSkill) ? '' : 'active'}}"><a href="{{route('student-behaviour-skill.index')}}">Skill Behaviour Entry</a></li>
            <li class="breadcrumb-item active">Edit</li>
        @else
            <li class="breadcrumb-item active">Skill Behaviour Entry</li>
        @endif
    </x-breadcrumb>
    @include('components.flash_message')
    <div class="row">
        <form class="needs-validation" method="POST" action="{{ isset($studentBehaviourSkill) ? route('student-behaviour-skill.update',$studentBehaviourSkill['id']) : route('student-behaviour-skill.store') }}" novalidate>
            @csrf
            @if(isset($studentBehaviourSkill))
                @method('PATCH')
            @endif
            @include('assessment.skill_behaviour.create_edit_skill_behaviour')
            @include('assessment.skill_behaviour.studentList')
        </form>
        @if(!isset($studentBehaviourSkill))
            @include('assessment.skill_behaviour.list')
        @endif
    </div>

    <div class="modal fade" id="skillModal" tabindex="-1" aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Skills</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="behaviourModal" tabindex="-1" aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Behaviours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

@endsection

