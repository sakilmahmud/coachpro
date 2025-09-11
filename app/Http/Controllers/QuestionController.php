<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Course;
use App\Models\MockTest;

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
        $validationRules = [
            'question' => 'required|string',
            'lavel' => 'nullable|string',
            'explanation' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'mock_test_id' => 'required|exists:mock_tests,id',
            'question_type' => 'required|in:MCQ,True/False,Multiple Answer',
        ];

        if ($request->question_type === 'MCQ') {
            $validationRules['mcq_answers'] = 'required|array|min:4|max:4';
            $validationRules['mcq_answers.*'] = 'required|string';
            $validationRules['mcq_correct_answer'] = 'required|numeric|min:0|max:3';
        } elseif ($request->question_type === 'True/False') {
            $validationRules['tf_answers'] = 'required|array|min:2|max:2';
            $validationRules['tf_answers.*'] = 'required|string';
            $validationRules['tf_correct_answer'] = 'required|numeric|in:0,1';
        } elseif ($request->question_type === 'Multiple Answer') {
            $validationRules['ma_answers'] = 'required|array|min:4|max:4';
            $validationRules['ma_answers.*'] = 'required|string';
            $validationRules['ma_correct_answers'] = 'required|array|min:1';
            $validationRules['ma_correct_answers.*'] = 'required|numeric';
        }

        $request->validate($validationRules);

        $question = Question::create([
            'question' => $request->question,
            'lavel' => $request->lavel,
            'explanation' => $request->explanation,
            'course_id' => $request->course_id,
            'mock_test_id' => $request->mock_test_id,
            'question_type' => $request->question_type,
        ]);

        if ($request->question_type === 'MCQ') {
            foreach ($request->mcq_answers as $index => $answerText) {
                Answer::create([
                    'questions_id' => $question->id,
                    'answer' => $answerText,
                    'is_correct' => ($index == $request->mcq_correct_answer) ? 1 : 0,
                ]);
            }
        } elseif ($request->question_type === 'True/False') {
            foreach ($request->tf_answers as $index => $answerText) {
                Answer::create([
                    'questions_id' => $question->id,
                    'answer' => $answerText,
                    'is_correct' => ($index == $request->tf_correct_answer) ? 1 : 0,
                ]);
            }
        } elseif ($request->question_type === 'Multiple Answer') {
            foreach ($request->ma_answers as $index => $answerText) {
                Answer::create([
                    'questions_id' => $question->id,
                    'answer' => $answerText,
                    'is_correct' => in_array($index, $request->ma_correct_answers) ? 1 : 0,
                ]);
            }
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
        $question = \App\Models\Question::with('answers')->findOrFail($id);
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::with('answers')->findOrFail($id);
        $mockTest = MockTest::findOrFail($question->mock_test_id);
        $course = Course::findOrFail($question->course_id);
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
        $validationRules = [
            'question' => 'required|string',
            'lavel' => 'nullable|string',
            'explanation' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'mock_test_id' => 'required|exists:mock_tests,id',
            'question_type' => 'required|in:MCQ,True/False,Multiple Answer',
        ];

        if ($request->question_type === 'MCQ') {
            $validationRules['mcq_answers'] = 'required|array|min:4|max:4';
            $validationRules['mcq_answers.*'] = 'required|string';
            $validationRules['mcq_correct_answer'] = 'required|numeric|min:0|max:3';
        } elseif ($request->question_type === 'True/False') {
            $validationRules['tf_answers'] = 'required|array|min:2|max:2';
            $validationRules['tf_answers.*'] = 'required|string';
            $validationRules['tf_correct_answer'] = 'required|numeric|in:0,1';
        } elseif ($request->question_type === 'Multiple Answer') {
            $validationRules['ma_answers'] = 'required|array|min:4|max:4';
            $validationRules['ma_answers.*'] = 'required|string';
            $validationRules['ma_correct_answers'] = 'required|array|min:1';
            $validationRules['ma_correct_answers.*'] = 'required|numeric';
        }

        $request->validate($validationRules);

        $question = Question::findOrFail($id);
        $question->update([
            'question' => $request->question,
            'lavel' => $request->lavel,
            'explanation' => $request->explanation,
            'course_id' => $request->course_id,
            'mock_test_id' => $request->mock_test_id,
            'question_type' => $request->question_type,
        ]);

        // Delete existing answers
        $question->answers()->delete();

        // Create new answers based on question type
        if ($request->question_type === 'MCQ') {
            foreach ($request->mcq_answers as $index => $answerText) {
                Answer::create([
                    'questions_id' => $question->id,
                    'answer' => $answerText,
                    'is_correct' => ($index == $request->mcq_correct_answer) ? 1 : 0,
                ]);
            }
        } elseif ($request->question_type === 'True/False') {
            foreach ($request->tf_answers as $index => $answerText) {
                Answer::create([
                    'questions_id' => $question->id,
                    'answer' => $answerText,
                    'is_correct' => ($index == $request->tf_correct_answer) ? 1 : 0,
                ]);
            }
        } elseif ($request->question_type === 'Multiple Answer') {
            foreach ($request->ma_answers as $index => $answerText) {
                Answer::create([
                    'questions_id' => $question->id,
                    'answer' => $answerText,
                    'is_correct' => in_array($index, $request->ma_correct_answers) ? 1 : 0,
                ]);
            }
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
