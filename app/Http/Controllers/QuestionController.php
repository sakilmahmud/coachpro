<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mockTestId = $request->query('mock_test_id');
        $mockTest = \App\Models\MockTest::findOrFail($mockTestId);
        $questions = \App\Models\Question::where('mock_test_id', $mockTestId)->get();
        return view('admin.questions.index', compact('mockTest', 'questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $mockTestId = $request->query('mock_test_id');
        $courseId = $request->query('course_id');
        $mockTest = \App\Models\MockTest::findOrFail($mockTestId);
        $course = \App\Models\Course::findOrFail($courseId);
        return view('admin.questions.create', compact('mockTest', 'course'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'lavel' => 'nullable|string',
            'explanation' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'mock_test_id' => 'required|exists:mock_tests,id',
            'answers' => 'required|array|min:4|max:4',
            'answers.*' => 'required|string',
            'correct_answer' => 'required|numeric|min:0|max:3',
        ]);

        $question = Question::create($request->all());

        foreach ($request->answers as $index => $answerText) {
            Answer::create([
                'questions_id' => $question->id,
                'answer' => $answerText,
                'is_correct' => ($index == $request->correct_answer) ? 1 : 0,
            ]);
        }

        return redirect()->route('questions.index', ['mock_test_id' => $request->mock_test_id])->with('success', 'Question created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = \App\Models\Question::findOrFail($id);
        $mockTest = \App\Models\MockTest::findOrFail($question->mock_test_id);
        $course = \App\Models\Course::findOrFail($question->course_id);
        return view('admin.questions.edit', compact('question', 'mockTest', 'course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string',
            'lavel' => 'nullable|string',
            'explanation' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'mock_test_id' => 'required|exists:mock_tests,id',
            'answers' => 'required|array|min:4|max:4',
            'answers.*' => 'required|string',
            'correct_answer' => 'required|numeric|min:0|max:3',
        ]);

        $question = Question::findOrFail($id);
        $question->update($request->all());

        // Delete existing answers
        $question->answers()->delete();

        // Create new answers
        foreach ($request->answers as $index => $answerText) {
            Answer::create([
                'questions_id' => $question->id,
                'answer' => $answerText,
                'is_correct' => ($index == $request->correct_answer) ? 1 : 0,
            ]);
        }

        return redirect()->route('questions.index', ['mock_test_id' => $question->mock_test_id])->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = \App\Models\Question::findOrFail($id);
        $mockTestId = $question->mock_test_id;
        $question->delete();

        return redirect()->route('questions.index', ['mock_test_id' => $mockTestId])->with('success', 'Question deleted successfully.');
    }

    public function getAnswers(Question $question)
    {
        return response()->json($question->answers);
    }
}
