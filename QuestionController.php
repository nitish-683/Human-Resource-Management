<?php

namespace App\Http\Controllers\Admin;

use App\Models\Question;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('question_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $questions = Question::latest()->paginate(15);
        return view('admin.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('question_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionRequest $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
        ]);

        Question::create($request->all());
        toast('Question created successfully.','success');
        return redirect()->route('admin.questions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(question $question)
    {
        abort_if(Gate::denies('question_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuestionRequest $request, question $question)
    {
        $request->validate([
            'question' => 'required|string|max:255',
        ]);

        $question->update($request->all());
        toast('Question updated successfully.','success');
        return redirect()->route('admin.questions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(question $question)
    {
        abort_if(Gate::denies('question_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $question->delete();
        toast('Question deleted successfully.','success');
        return redirect()->route('admin.questions.index');
    }
}
