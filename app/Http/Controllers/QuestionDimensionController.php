<?php

namespace App\Http\Controllers;

use App\Models\QuestionDimension;
use Illuminate\Http\Request;

class QuestionDimensionController extends Controller
{
    public function index()
    {
        $questionDimensions = QuestionDimension::all();
        // dd($questionDimensions);
        return view('question-dimensions.index', compact('questionDimensions'));
    }

    public function create()
    {
        return view('question-dimensions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        QuestionDimension::create($request->all());

        return redirect()->route('question-dimensions.index')
            ->with('success', 'Question Dimension created successfully');
    }

    public function show(QuestionDimension $questionDimension)
    {
        return view('question-dimensions.show', compact('questionDimension'));
    }

    public function edit(QuestionDimension $questionDimension)
    {
        return view('question-dimensions.edit', compact('questionDimension'));
    }

    public function update(Request $request, QuestionDimension $questionDimension)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $questionDimension->update($request->all());

        return redirect()->route('question-dimensions.index')
            ->with('success', 'Question Dimension updated successfully');
    }

    public function destroy(QuestionDimension $questionDimension)
    {
        $questionDimension->delete();

        return redirect()->route('question-dimensions.index')
            ->with('success', 'Question Dimension deleted successfully');
    }
}
