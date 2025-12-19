<?php

namespace App\Http\Controllers;

use App\Models\AnswerDimension;
use Illuminate\Http\Request;
use App\Models\QuestionDimension;

class AnswerDimensionController extends Controller
{
    public function index()
    {
        $answerDimensions = AnswerDimension::all();
        return view('answer-dimensions.index', compact('answerDimensions'));
    }

    public function create()
    {
        $questionDimensions = QuestionDimension::all();
        return view('answer-dimensions.create', compact('questionDimensions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_dimensions_id' => 'required|exists:question_dimensions,id',
            'title' => 'required|max:255',
        ]);

        AnswerDimension::create($request->all());

        return redirect()->route('answer-dimensions.index')
            ->with('success', 'Answer Dimension created successfully');
    }

    public function show(AnswerDimension $answerDimension)
    {
        return view('answer-dimensions.show', compact('answerDimension'));
    }

    public function edit(AnswerDimension $answerDimension)
    {
        $questionDimensions = QuestionDimension::all();
        return view('answer-dimensions.edit', compact('answerDimension', 'questionDimensions'));
    }


    public function update(Request $request, AnswerDimension $answerDimension)
    {
        $request->validate([
            'question_dimensions_id' => 'required|exists:question_dimensions,id',
            'title' => 'required|max:255',
        ]);

        $answerDimension->update($request->all());

        return redirect()->route('answer-dimensions.index')
            ->with('success', 'Answer Dimension updated successfully');
    }

    public function destroy(AnswerDimension $answerDimension)
    {
        $answerDimension->delete();

        return redirect()->route('answer-dimensions.index')
            ->with('success', 'Answer Dimension deleted successfully');
    }
}
