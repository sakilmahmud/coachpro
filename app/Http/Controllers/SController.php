<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Answer;
use App\Models\Subject;
use App\Models\User;
use App\Models\Access;
use App\Models\Pdf;
use App\Models\Videolink;
use App\Models\UserTestData;
use App\Models\FlashQuestion;
use App\Models\Result;
use App\Models\Question;
use App\Models\StudentQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SController extends Controller
{     
    public function mocktest($testsubject, $title)
{
    $user = Auth::user();
    $userId = $user->id;

    $subjectIds = Access::where('student_id', $userId)->pluck('subject_id')->toArray();
    $subjects = Subject::whereIn('id', $subjectIds)->pluck('titel');

    $questionIds = [];

    foreach ($subjects as $subject) {
        $questionIds[] = Question::where('subject', $subject)->where('subject', $testsubject)->where('title', $title)->pluck('id')->toArray();
    }

    $questions = [];
    $answers = [];
    $correctAnswers = []; // Add this array to store correct answers

    foreach ($questionIds as $subjectQuestions) {
        foreach ($subjectQuestions as $questionId) {
            $questions[] = Question::find($questionId);
            $answers[] = Answer::where('questions_id', $questionId)->get();
            // Fetch and store the correct answer IDs for each question
            $correctAnswers[] = Answer::where('questions_id', $questionId)->where('is_correct', 1)->pluck('id')->toArray();
        }
                    }
                //dd( $correctAnswers);
                    return view('student.mocktest', [
                        'answers' => $answers,
                        'questions' => $questions,
                        'correctAnswers' => $correctAnswers, // Pass correct answers to the view
                    ]);
                }


                public function showResult(Request $request)
{
    $user = Auth::user();
    $userId = $user->id;

    // Assuming you have received the data from the URL query parameters
    $correctCount = $_GET['correctCount'];
    $incorrectCount = $_GET['incorrectCount'];
    $unattemptedCount = $_GET['unattemptedCount'];
    $percentage = $_GET['percentage'];
    $questionIds = json_decode($_GET['questionIds']);
    $selectedOptions = json_decode($_GET['selectedOptions']);

    // Fetch option names for selected options
    $optionNames = Answer::whereIn('id', $selectedOptions)->pluck('answer', 'id');

    // Fetch is_correct values for selected options
    $correctAnswers = Answer::whereIn('questions_id', $questionIds)
        ->where('is_correct', 1)
        ->pluck('answer', 'questions_id');

    // Fetch question details for the questions
    $questions = Question::whereIn('id', $questionIds)->get();

    // Determine the current attempt number for the user based on title and subject
    $currentAttempt = $this->getCurrentAttempt($userId, $questions[0]->title, $questions[0]->subject);

    $result = new Result;
    $result->user_id = $userId;
    $result->subject = $questions[0]->subject;
    $result->title = $questions[0]->title;
    $result->attempt_id = $currentAttempt;
    $result->percentage = $percentage;
    $result->correct_count = $correctCount;
    $result->incorrect_count = $incorrectCount;
    $result->unattempted_count = $unattemptedCount;

    $result->save();

    foreach ($questionIds as $index => $questionId) {
        // Create a new record for the current attempt
        $userAnswer = new UserTestData;
        $userAnswer->user_id = $userId;
        $userAnswer->question_id = $questionIds[$index]; // Store the question text
        $userAnswer->question_name = $questions[$index]->question; // Store the question text
        $userAnswer->selected_option = $optionNames[$selectedOptions[$index]] ?? null;
        $userAnswer->is_correct = $correctAnswers[$questionId] ?? null;
        $userAnswer->attempt_id = $currentAttempt; // Set the current attempt number
        $userAnswer->subject = $questions[$index]->subject; // Store the subject
        $userAnswer->title = $questions[$index]->title; // Store the title

        $userAnswer->save();
    }

    return view('student.result', compact('correctCount', 'incorrectCount', 'percentage', 'unattemptedCount'));
}

private function getCurrentAttempt($userId, $title, $subject)
{
    // Find the latest attempt for the user with the given title and subject
    $latestAttempt = UserTestData::where('user_id', $userId)
        ->where('title', $title)
        ->where('subject', $subject)
        ->max('attempt_id');

    // If there is no previous attempt, start from 1; otherwise, increment the attempt number
    $nextAttempt = $latestAttempt !== null ? $latestAttempt + 1 : 1;

    // Ensure that the attempt number is reset to 1 if all previous attempts are deleted
    $existingAttempts = UserTestData::where('user_id', $userId)
        ->where('title', $title)
        ->where('subject', $subject)
        ->pluck('attempt_id')
        ->unique();

    if ($existingAttempts->count() === 0) {
        return 1; // No previous attempts, start from 1
    }

    // Check if there are any gaps in the attempt numbers and fill the first gap
    $sortedAttempts = $existingAttempts->sort();
    for ($attempt = 1; $attempt <= $sortedAttempts->last(); $attempt++) {
        if (!$sortedAttempts->contains($attempt)) {
            return $attempt;
        }
    }

    // If there are no gaps, return the next attempt number
    return $nextAttempt;
}





                
                
    ////Mock Test 1
    public function mocktest1()
    {
        $user = Auth::user();
        $userId = $user->id;
    
        $subjectIds = Access::where('student_id', $userId)->pluck('subject_id')->toArray();
        $subjects = Subject::whereIn('id', $subjectIds)->pluck('titel');
        $title = Question::whereIn('subject' , $subjects)->pluck('title');

        $allTitles = Question::where('title', 'test 1')->where('subject', $subjects )->get();

        
          //dd(  $allTitles);
    
        return view('student.mocktest1', ['titles' => $allTitles]);
    }
        ///////Mock Test 2
        public function mocktest2()
    {
        $user = Auth::user();
        $userId = $user->id;
    
        $subjectIds = Access::where('student_id', $userId)->pluck('subject_id')->toArray();
        $subjects = Subject::whereIn('id', $subjectIds)->pluck('titel');
        $title = Question::whereIn('subject' , $subjects)->pluck('title');

        $allTitles = Question::where('title', 'test 2')->where('subject', $subjects )->get();

        
          //dd(  $allTitles);
    
        return view('student.mocktest1', ['titles' => $allTitles]);
    }
    ////Mock test 3
    public function mocktest3()
    {
        $user = Auth::user();
        $userId = $user->id;
    
        $subjectIds = Access::where('student_id', $userId)->pluck('subject_id')->toArray();
        $subjects = Subject::whereIn('id', $subjectIds)->pluck('titel');
        $title = Question::whereIn('subject' , $subjects)->pluck('title');

        $allTitles = Question::where('title', 'test 3')->where('subject', $subjects )->get();

        
          //dd(  $allTitles);
    
        return view('student.mocktest1', ['titles' => $allTitles]);
    }
    ////Mock test 4
    public function mocktest4()
    {
        $user = Auth::user();
        $userId = $user->id;
    
        $subjectIds = Access::where('student_id', $userId)->pluck('subject_id')->toArray();
        $subjects = Subject::whereIn('id', $subjectIds)->pluck('titel');
        $title = Question::whereIn('subject' , $subjects)->pluck('title');

        $allTitles = Question::where('title', 'test 4')->where('subject', $subjects )->get();

        
          //dd(  $allTitles);
    
        return view('student.mocktest1', ['titles' => $allTitles]);
    }
    ////Mock Test 5
    public function mocktest5()
    {
        $user = Auth::user();
        $userId = $user->id;
    
        $subjectIds = Access::where('student_id', $userId)->pluck('subject_id')->toArray();
        $subjects = Subject::whereIn('id', $subjectIds)->pluck('titel');
        $title = Question::whereIn('subject' , $subjects)->pluck('title');

        $allTitles = Question::where('title', 'test 5')->where('subject', $subjects )->get();

        
          //dd(  $allTitles);
    
        return view('student.mocktest1', ['titles' => $allTitles]);
    }
     public function mocktest6()
    {
        $user = Auth::user();
        $userId = $user->id;
    
        $subjectIds = Access::where('student_id', $userId)->pluck('subject_id')->toArray();
        $subjects = Subject::whereIn('id', $subjectIds)->pluck('titel');
        $title = Question::whereIn('subject' , $subjects)->pluck('title');

        $allTitles = Question::where('title', 'test 6')->where('subject', $subjects )->get();

        
          //dd(  $allTitles);
    
        return view('student.mocktest1', ['titles' => $allTitles]);
    }
     public function mocktest7()
    {
        $user = Auth::user();
        $userId = $user->id;
    
        $subjectIds = Access::where('student_id', $userId)->pluck('subject_id')->toArray();
        $subjects = Subject::whereIn('id', $subjectIds)->pluck('titel');
        $title = Question::whereIn('subject' , $subjects)->pluck('title');

        $allTitles = Question::where('title', 'test 7')->where('subject', $subjects )->get();

        
          //dd(  $allTitles);
    
        return view('student.mocktest1', ['titles' => $allTitles]);
    }
    public function flashcard()

    {

        
            $user = Auth::user();

            // Now you have the user's ID in $user->id
            $userId = $user->id;
            // Retrieve the user by ID
            
            //dd($userId);
            $subjectIds = Access::where('student_id', $userId)->pluck('subject_id')->toArray();

            // Get course IDs associated with these subjects
            $courseIds = Subject::whereIn('id', $subjectIds)->pluck('course_id')->unique()->toArray();

            // Fetch flash questions based on course_id
            $flash = FlashQuestion::whereIn('course_id', $courseIds)->paginate(1);

            
    

        //             dd(  $flash);
        //             return view('student.dashboard',['exams'=>$subjects]);
        // $exams = FlashQuestion::paginate(1);
        return view('student.flashcard',['exams'=>$flash]);
    }

    public function querytext()
    {
        return view('student.studentquery');
    }

   

    public function studentQuery(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'number' => 'required|string|max:15',
        'query' => 'required|string',
        'attachment' => 'nullable|file|mimes:pdf,jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $data = new StudentQuery();
    $data->student_name = $request['name'];
    $data->student_mail = $request['email'];
    $data->contry = $request['country'];
    $data->student_number = $request['number'];
    $data->student_querys = $request['query'];

    if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $fileName = time().'.'.$file->getClientOriginalExtension();
        $destinationPath = public_path('uploads/student_queries');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $file->move($destinationPath, $fileName);
        $data->attachment = $fileName;
    }

    $data->save();

    return redirect()->back()->with('success', 'Your query has been submitted successfully!');
}

            public function loadNextSection()
        {
            // Logic to determine and load the next section
            $nextSectionHtml = view('student.next-section')->render();

            return response()->json(['html' => $nextSectionHtml]);
        }

     public function studypdf()
     {
        $user = Auth::user();
        $userId = $user->id;
        $subjectIds = Access::where('student_id', $userId)->pluck('subject_id')->toArray();
        $pdfs = Pdf::whereIn('subject_id', $subjectIds)->get();

        // Get enrolled courses for consistent layout
        $enrolledSubjectIds = Access::where('student_id', $userId)->pluck('subject_id');
        $enrolledCourseIds = Subject::whereIn('id', $enrolledSubjectIds)->pluck('course_id')->unique();
        $enrolledCourses = \App\Models\Course::whereIn('id', $enrolledCourseIds)->get();

        return view('student.study-materials-pdfs', compact('pdfs', 'enrolledCourses'));
     }
     public function studyvideo()
     {

        $user = Auth::user();
        $userId = $user->id;
        $subjectIds = Access::where('student_id', $userId)->pluck('subject_id')->toArray();
        $videolinks = Videolink::whereIn('subject_id', $subjectIds)->get();

        // Get enrolled courses for consistent layout
        $enrolledSubjectIds = Access::where('student_id', $userId)->pluck('subject_id');
        $enrolledCourseIds = Subject::whereIn('id', $enrolledSubjectIds)->pluck('course_id')->unique();
        $enrolledCourses = \App\Models\Course::whereIn('id', $enrolledCourseIds)->get();

        return view('student.study-materials-videos', compact('videolinks', 'enrolledCourses'));
     }

     public function testreview1()
        {
            $user = Auth::user();
            $userId = $user->id;

            // Assuming you have received the data from the URL query parameters
            $results = Result::where('user_id', $userId)
                ->where('title', 'like', '%test 1%')
                ->get();

            return view('student.testview', [
                'results' => $results,
            ]);
        }


        public function testreview2()
        {
            $user = Auth::user();
            $userId = $user->id;

            // Assuming you have received the data from the URL query parameters
            $results = Result::where('user_id', $userId)
                ->where('title', 'like', '%test 2%')
                ->get();

            return view('student.testview', [
                'results' => $results,
            ]);
        }

        public function testreview3()
        {
            $user = Auth::user();
            $userId = $user->id;

            // Assuming you have received the data from the URL query parameters
            $results = Result::where('user_id', $userId)
                ->where('title', 'like', '%test 3%')
                ->get();

            return view('student.testview', [
                'results' => $results,
            ]);
        }

        public function testreview4()
        {
            $user = Auth::user();
            $userId = $user->id;

            // Assuming you have received the data from the URL query parameters
            $results = Result::where('user_id', $userId)
                ->where('title', 'like', '%test 4%')
                ->get();

            return view('student.testview', [
                'results' => $results,
            ]);
        }

        public function testreview5()
        {
            $user = Auth::user();
            $userId = $user->id;

            // Assuming you have received the data from the URL query parameters
            $results = Result::where('user_id', $userId)
                ->where('title', 'like', '%test 5%')
                ->get();

            return view('student.testview', [
                'results' => $results,
            ]);
        }


        public function mockreview($attempt_id, $title, $subject)
        {
            $user = Auth::user();
            $userId = $user->id;
        
                // Fetch the explanations from the database and organize them as an associative array
        $explanations = Question::whereIn('id', function ($query) use ($title) {
            $query->select('question_id')
                ->from('user_test_data')
                ->where('title', $title);
        })->pluck('explaination', 'question')->toArray();

        $options = Answer::whereIn('questions_id', function ($query) use ($title) {
            $query->select('id')
                ->from('questions')
                ->where('title', $title);
        })->get(['questions_id', 'answer'])->groupBy('questions_id')->toArray();

        // Fetch the user's test data for attempted questions
        $attemptedQuestions = UserTestData::where('user_id', $userId)
            ->where('attempt_id', $attempt_id)
            ->where('title', $title)
            ->with('question.usertestdata')
            ->get();

           
        $questionIds = $attemptedQuestions->pluck('question.id')->toArray();
        $unattemptedQuestions = Question::whereNotIn('id', $questionIds)
        ->where('subject' ,$subject)
        ->where('title', $title)
       ->pluck('id');

        $unattemptcorrectAnswers = Answer::whereIn('questions_id', $unattemptedQuestions)
        ->where('is_correct', 1)
        ->pluck('answer');


        // Fetch all questions for the specific subject
        $allQuestions = Question::whereIn('id', function ($query) use ($title ,$subject) {
            $query->select('id')
                ->from('questions')
                ->where('title', $title )
                ->where('subject', $subject); // Add the subject filter
        })->get(['id', 'question'])->toArray();
//dd( $correctAnswers);
        return view('student.mockview', [
            'result' => $attemptedQuestions,
            'explanation' => $explanations,
            'options' => $options,
            'allQuestions' => $allQuestions,
            'subject' => $subject,
            'unattempt' => $unattemptedQuestions,
            'unattemptedcorrectanswer' =>  $unattemptcorrectAnswers

        ]);

                
                
        }

}
