<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Result;
use App\Models\QnaExam;
use App\Models\ExamAnswer;
use App\Models\Package;
use App\Models\ExamPayments;
use App\Models\Access;
use App\Models\Pdf;
use App\Models\Videolink;
use App\Models\StudentQuery;
use App\Models\FlashQuestion;
use App\Imports\QnaImport;
use App\Exports\ExportStudent;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{

    public function adminDashboard()
    {
        $batchCount = Subject::count();
        $studentCount = User::where('is_admin', 0)->count();

        return view('admin.dashboard', compact('batchCount', 'studentCount'));
    }
    public function batches()
    {
        $subjects = Subject::all();
        return view('admin.batches', compact('subjects'));
    }
    //add subject
    public function addSubject(Request $request)
    {
        try {

            Subject::insert([
                'subject' => $request->subject,
                'titel' => $request->title,
                'duration' => $request->duration,
                'starting_date' => $request->startdate,
                'end_date' => $request->enddate,
                'explnation' => $request->explanation
            ]);

            return response()->json(['success' => true, 'msg' => 'Subject added Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        };
    }

    //edit subject with ajax 
    public function editSubject(Request $request)
    {
        try {

            $subject = Subject::find($request->id);
            $subject->subject = $request->subject;
            $subject->titel = $request->title;
            $subject->duration = $request->duration;
            $subject->starting_date = $request->startdate;
            $subject->end_date = $request->enddate;
            $subject->explnation = $request->explnation;
            $subject->save();
            return response()->json(['success' => true, 'msg' => 'Subject updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        };
    }

    //delete subject
    public function deleteSubject(Request $request)
    {
        try {

            Subject::where('id', $request->id)->delete();
            return response()->json(['success' => true, 'msg' => 'Subject deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        };
    }

    ////Add pdf


    public function addpdf(Request $request)


    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf', // PDF file validation rule
        ]);

        // Retrieve the original file name
        $originalFileName = $request->file('pdf')->getClientOriginalName();

        // Save the PDF to a storage disk (e.g., 'public' disk)
        $path = $request->file('pdf')->storeAs('pdfs', $originalFileName, 'public');

        if (!$path) {
            return response()->json(['success' => false, 'msg' => 'Failed to save the PDF.']);
        }

        // Store the file path in the database
        Pdf::insert([
            'subject_id' => $request->subject_id,
            'topic' => $request->topic,
            'pdf' => $path,
        ]);

        $subjects = Subject::all();
        $exams = Exam::with('subjects')->get();

        return view('admin.dashboard', ['subjects' => $subjects, 'exams' => $exams]);
    }

    ////Show pdf


    public function showPdf($id)
    {
        $subjects = Pdf::all()->where('subject_id', $id);
        return view('admin.records.showpdf', ['chal' =>  $subjects]);
    }

    ///// Show pdf though js

    public function fetchData(Request $request)
    {
        // Apne data source se (e.g., database) data fetch karein
        $data = Videolink::where(/* aapke query conditions */)->get();

        // Data ko JSON response ke roop me return karein
        return response()->json(['data' => $data]);
    }



    ////Delete pdf
    public function deletepdf($id)
    {
        $record = Pdf::find($id);
        $record->delete();
        $subjects = Subject::all();
        $exams = Exam::with('subjects')->get();

        return view('admin.dashboard', ['subjects' => $subjects, 'exams' => $exams]);
    }

    ////Add Video Link
    public function addlink(Request $request)
    {
        try {

            Videolink::insert([
                'subject_id' => $request->exam_id,
                'topic' => $request->topic,
                'link' => $request->link

            ]);

            return response()->json(['success' => true, 'msg' => 'Subject added Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        };
    }
    ///////////Show video link
    public function showlink($id)
    {
        // Fetch Pdf data based on the subjectId
        $subjects = Videolink::all()->where('subject_id', $id);

        // dd($pdf);
        return view('admin.records.showlink', ['chal' =>  $subjects]);
    }

    ///// Delete Video Link
    public function deletelink($id)
    {
        $record = Videolink::find($id);
        $record->delete();
        $subjects = Subject::all();
        $exams = Exam::with('subjects')->get();

        return view('admin.dashboard', ['subjects' => $subjects, 'exams' => $exams]);
    }
    //exam dashboard load
    public function examDashboard()
    {
        $subjects = Subject::all();
        $exams = Exam::with('subjects')->get();
        // echo $exams;
        // dd();
        return view('admin.exam-dashboard', ['subjects' => $subjects, 'exams' => $exams]);
    }

    //add exam
    public function addExam(Request $request)
    {
        try {
            $plan = $request->plan;
            $prices = null;

            if (isset($request->inr) && isset($request->usd)) {
                $prices = json_encode(['INR' => $request->inr, 'USD' => $request->usd]);
            }

            $unique_id = uniqid('exid');
            Exam::insert([
                'exam_name' => $request->exam_name,
                'subject_id' => $request->subject_id,
                'date' => $request->date,
                'time' => $request->time,
                'attempt' => $request->attempt,
                'enterance_id' => $unique_id,
                'plan'   => $plan,
                'prices'   => $prices
            ]);
            return response()->json(['success' => true, 'msg' => 'Exam added Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        };
    }

    public function getExamDetail($id)
    {
        try {

            $exam = Exam::where('id', $id)->get();
            return response()->json(['success' => true, 'data' => $exam]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        };
    }

    public function updateExam(Request $request)
    {
        try {

            $plan = $request->plan;
            $prices = null;

            if (isset($request->inr) && isset($request->usd)) {
                $prices = json_encode(['INR' => $request->inr, 'USD' => $request->usd]);
            }

            $exam = Exam::find($request->exam_id);
            $exam->exam_name = $request->exam_name;
            $exam->subject_id = $request->subject_id;
            $exam->date = $request->date;
            $exam->time = $request->time;
            $exam->attempt = $request->attempt;
            $exam->plan = $plan;
            $exam->prices = $prices;
            $exam->save();
            return response()->json(['success' => true, 'msg' => 'Exam updated successfull!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        };
    }

    //delete Exam
    public function deleteExam(Request $request)
    {
        try {

            Exam::where('id', $request->exam_id)->delete();
            return response()->json(['success' => true, 'msg' => 'Exam deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        };
    }

    public function qnaDashboard()
    {
        $questions = Question::with('answers')->get();
        return view('admin.qnaDashboard', compact('questions'));
    }

    //Show PfMP subject Q&A


    public function pfmptest1()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 1')
            ->where('subject', 'RfMP')
            ->get();

        return view('admin.swowpfmptest1', compact('questions'));
    }
    public function pfmptest2()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 2')
            ->where('subject', 'RfMP')
            ->get();

        return view('admin.swowpfmptest2', compact('questions'));
    }
    public function pfmptest3()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 3')
            ->where('subject', 'RfMP')
            ->get();

        return view('admin.swowpfmptest3', compact('questions'));
    }
    public function pfmptest4()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 4')
            ->where('subject', 'RfMP')
            ->get();

        return view('admin.swowpfmptest4', compact('questions'));
    }
    public function pfmptest5()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 5')
            ->where('subject', 'RfMP')
            ->get();

        return view('admin.swowpfmptest5', compact('questions'));
    }
    public function pfmptest6()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 6')
            ->where('subject', 'RfMP')
            ->get();

        return view('admin.swowpfmptest6', compact('questions'));
    }
    public function pfmptest7()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 7')
            ->where('subject', 'RfMP')
            ->get();

        return view('admin.swowpfmptest7', compact('questions'));
    }
    ////PgMP show question Answer
    public function pgmptest1()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 1')
            ->where('subject', 'PgMP')
            ->get();

        return view('admin.swowpgmptest1', compact('questions'));
    }
    public function pgmptest2()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 2')
            ->where('subject', 'PgMP')
            ->get();

        return view('admin.swowpgmptest2', compact('questions'));
    }
    public function pgmptest3()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 3')
            ->where('subject', 'PgMP')
            ->get();

        return view('admin.swowpgmptest3', compact('questions'));
    }
    public function pgmptest4()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 4')
            ->where('subject', 'PgMP')
            ->get();

        return view('admin.swowpgmptest4', compact('questions'));
    }
    public function pgmptest5()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 5')
            ->where('subject', 'PgMP')
            ->get();

        return view('admin.swowpgmptest5', compact('questions'));
    }
    public function pgmptest6()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 6')
            ->where('subject', 'PgMP')
            ->get();

        return view('admin.swowpgmptest6', compact('questions'));
    }
    public function pgmptest7()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 7')
            ->where('subject', 'PgMP')
            ->get();

        return view('admin.swowpgmptest7', compact('questions'));
    }
    ////PMP question  show code 
    public function pmptest1()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 1')
            ->where('subject', 'PMP')
            ->get();

        return view('admin.swowpmptest1', compact('questions'));
    }
    public function pmptest2()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 2')
            ->where('subject', 'PMP')
            ->get();

        return view('admin.swowpmptest2', compact('questions'));
    }
    public function pmptest3()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 3')
            ->where('subject', 'PMP')
            ->get();

        return view('admin.swowpmptest3', compact('questions'));
    }
    public function pmptest4()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 4')
            ->where('subject', 'PMP')
            ->get();

        return view('admin.swowpmptest4', compact('questions'));
    }
    public function pmptest5()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 5')
            ->where('subject', 'PMP')
            ->get();

        return view('admin.swowpmptest5', compact('questions'));
    }
    public function pmptest6()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 6')
            ->where('subject', 'PMP')
            ->get();

        return view('admin.swowpmptest6', compact('questions'));
    }
    public function pmptest7()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 7')
            ->where('subject', 'PMP')
            ->get();

        return view('admin.swowpmptest7', compact('questions'));
    }
    ///PMI-ACP Show question code
    public function pmiacptest1()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 1')
            ->where('subject', 'PMI-ACP')
            ->get();

        return view('admin.swowpmiacptest1', compact('questions'));
    }
    public function pmiacptest2()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 2')
            ->where('subject', 'PMI-ACP')
            ->get();

        return view('admin.swowpmiacptest2', compact('questions'));
    }
    public function pmiacptest3()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 3')
            ->where('subject', 'PMI-ACP')
            ->get();

        return view('admin.swowpmiacptest3', compact('questions'));
    }
    public function pmiacptest4()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 4')
            ->where('subject', 'PMI-ACP')
            ->get();

        return view('admin.swowpmiacptest4', compact('questions'));
    }
    public function pmiacptest5()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 5')
            ->where('subject', 'PMI-ACP')
            ->get();

        return view('admin.swowpmiacptest5', compact('questions'));
    }
    public function pmiacptest6()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 6')
            ->where('subject', 'PMI-ACP')
            ->get();

        return view('admin.swowpmiacptest6', compact('questions'));
    }
    public function pmiacptest7()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 7')
            ->where('subject', 'PMI-ACP')
            ->get();

        return view('admin.swowpmiacptest7', compact('questions'));
    }
    ////PMI-RMP Question Show
    public function pmirmptest1()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 1')
            ->where('subject', 'PMI-RMP')
            ->get();

        return view('admin.swowpmirmptest1', compact('questions'));
    }
    public function pmirmptest2()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 2')
            ->where('subject', 'PMI-RMP')
            ->get();

        return view('admin.swowpmirmptest2', compact('questions'));
    }
    public function pmirmptest3()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 3')
            ->where('subject', 'PMI-RMP')
            ->get();

        return view('admin.swowpmirmptest3', compact('questions'));
    }
    public function pmirmptest4()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 4')
            ->where('subject', 'PMI-RMP')
            ->get();

        return view('admin.swowpmirmpptest4', compact('questions'));
    }
    public function pmirmptest5()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 5')
            ->where('subject', 'PMI-RMP')
            ->get();

        return view('admin.swowpmirmptest5', compact('questions'));
    }
    public function pmirmptest6()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 6')
            ->where('subject', 'PMI-RMP')
            ->get();

        return view('admin.swowpmirmptest6', compact('questions'));
    }
    public function pmirmptest7()
    {
        $questions = Question::with('answers')
            ->where('title', 'test 7')
            ->where('subject', 'PMI-RMP')
            ->get();

        return view('admin.swowpmirmptest7', compact('questions'));
    }




    //add q&a
    public function addQna(Request $request)
    {
        /* print_r($_POST);
        die; */
        try {

            $explaination = null;
            if (isset($request->explaination)) {
                $explaination = $request->explaination;
            }

            $questionId = Question::insertGetId([
                'subject' => $request->subject,
                'title' => $request->title,
                'lavel' => $request->label,
                'explanation' => $request->explanation,
                'question' => $request->question,
                'explaination' => $explaination
            ]);

            foreach ($request->answers as $answer) {

                $is_correct = 0;
                if ($request->is_correct == $answer) {
                    $is_correct = 1;
                }

                Answer::insert([
                    'questions_id' => $questionId,
                    'answer' => $answer,
                    'is_correct' => $is_correct
                ]);
            }

            return response()->json(['success' => true, 'msg' => 'Exam deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        };
    }

    public function flashDashboard()
    {
        $flashData = FlashQuestion::get();
        return view('admin.flashDashboard', ['chal' =>  $flashData]);
    }
    //// Add Flash
    public function addflash(Request $request)
    {
        // Your validation logic here
        $request->validate([
            'subject' => 'required',
            'question' => 'required',
            'answer' => 'required',
        ]);

        $flashsave = new FlashQuestion();

        $flashsave->subject = $request->input('subject');
        $flashsave->question = $request->input('question');
        $flashsave->answer = $request->input('answer');

        $flashsave->save();


        $flashData = FlashQuestion::all();


        return view('admin.flashDashboard', ['chal' =>  $flashData]);
    }



    public function getQnaDetails(Request $request)
    {
        $qna = Question::where('id', $request->qid)->with('answers')->get();

        return response()->json(['data' => $qna]);
    }

    public function deleteAns(Request $request)
    {
        Answer::where('id', $request->id)->delete();
        return response()->json(['success' => true, 'msg' => 'Answer deleted successfully!']);
    }

    public function updateQna(Request $request)
    {
        try {

            $explaination = null;
            if (isset($request->explaination)) {
                $explaination = $request->explaination;
            }

            Question::where('id', $request->question_id)->update([
                'question' => $request->question,
                'explaination' => $explaination
            ]);

            //old answer update
            if (isset($request->answers)) {

                foreach ($request->answers as $key => $value) {

                    $is_correct = 0;
                    if ($request->is_correct == $value) {
                        $is_correct = 1;
                    }

                    Answer::where('id', $key)
                        ->update([
                            'questions_id' => $request->question_id,
                            'answer' => $value,
                            'is_correct' => $is_correct
                        ]);
                }
            }

            //new answers added
            if (isset($request->new_answers)) {

                foreach ($request->new_answers as $answer) {

                    $is_correct = 0;
                    if ($request->is_correct == $answer) {
                        $is_correct = 1;
                    }

                    Answer::insert([
                        'questions_id' => $request->question_id,
                        'answer' => $answer,
                        'is_correct' => $is_correct
                    ]);
                }
            }

            return response()->json(['success' => true, 'msg' => 'Q&A updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        };
    }

    public function deleteQna(Request $request)
    {
        Question::where('id', $request->id)->delete();
        Answer::where('questions_id', $request->id)->delete();

        return response()->json(['success' => true, 'msg' => 'Q&A deleted successfully!']);
    }

    public function importQna(Request $request)
    {
        try {

            Excel::import(new QnaImport, $request->file('file'));

            return response()->json(['success' => true, 'msg' => 'Import Q&A successfuly!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    //student dashboard
    public function studentsDashboard()
    {
        $students = User::where('is_admin', 0)->get();
        return view('admin.studentsDashboard', compact('students'));
    }

    //add Student

    public function addStudent(Request $request)
    {
        try {

            $password = Str::random(8);

            User::insert([
                'name' => $request->name,
                'email' => $request->email,
                'phone_no' => $request->phone,
                'country' => $request->country,
                'altphone_no' => $request->uphone,
                'address' => $request->addtess,
                'image' => $request->image,
                'password' => Hash::make($password)
            ]);

            $url = URL::to('/');

            $data['url'] = $url;
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = $password;
            $data['title'] = "Student Registration on OES";

            Mail::send('registrationMail', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
            return response()->json(['success' => true, 'msg' => 'Student added Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    //update student
    public function editStudent(Request $request)
    {
        try {

            $user = User::find($request->id);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_no = $request->phone;
            $user->country = $request->country;
            $user->altphone_no = $request->uphone;
            $user->address = $request->address;
            $user->image = $request->image;

            $user->save();

            $url = URL::to('/');

            $data['url'] = $url;
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['title'] = "Updated Student Profile on OES";

            Mail::send('updateProfileMail', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
            return response()->json(['success' => true, 'msg' => 'Student updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    //delete student
    public function deleteStudent(Request $request)
    {
        try {

            User::where('id', $request->id)->delete();
            ExamPayments::where('user_id', $request->id)->delete();
            return response()->json(['success' => true, 'msg' => 'Student deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function exportStudents()
    {
        return Excel::download(new ExportStudent, 'students.xlsx');
    }

    //get questions
    public function getQuestions(Request $request)
    {
        try {

            $questions = Question::all();

            if (count($questions) > 0) {

                $data = [];
                $counter = 0;

                foreach ($questions as $question) {
                    $qnaExam = QnaExam::where(['exam_id' => $request->exam_id, 'question_id' => $question->id])->get();
                    if (count($qnaExam) == 0) {
                        $data[$counter]['id'] = $question->id;
                        $data[$counter]['questions'] = $question->question;
                        $counter++;
                    }
                }
                return response()->json(['success' => true, 'msg' => 'Questions data!', 'data' => $data]);
            } else {
                return response()->json(['success' => false, 'msg' => 'Questions not Found!']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    ///fatch students for subject access
    public function getStudent(Request $request)
    {
        try {
            $users = User::select('id', 'email', 'name')->get();

            if ($users->isEmpty()) {
                return response()->json(['success' => false, 'msg' => 'Users not Found!']);
            }

            $data = [];
            $counter = 0;

            foreach ($users as $student) {
                // Here, you can access $student->email and $student->name directly
                $qnaExam = User::where('id', $request->exam_id)
                    ->where('email', $student->id)
                    ->where('is_admin', 0) // Filter only students with is_admin set to 0
                    ->get();

                if ($qnaExam->isEmpty()) {
                    $data[$counter]['id'] = $student->id;
                    $data[$counter]['email'] = $student->email;
                    $data[$counter]['name'] = $student->name; // Add the 'name' column to the data array
                    $counter++;
                }
            }

            return response()->json(['success' => true, 'msg' => 'User data!', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    ////Add question for Exam 
    public function addQuestions(Request $request)
    {
        try {

            if (isset($request->questions_ids)) {

                foreach ($request->questions_ids as $qid) {
                    QnaExam::insert([
                        'exam_id' => $request->exam_id,
                        'question_id' => $qid
                    ]);
                }
            }
            return response()->json(['success' => true, 'msg' => 'Questions added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    ////Add Student for Subject Access
    public function addStudents(Request $request)
    {
        try {

            if (isset($request->questions_ids)) {

                foreach ($request->questions_ids as $qid) {
                    Access::insert([
                        'subject_id' => $request->exam_id,
                        'student_id' => $qid
                    ]);
                }
            }
            return response()->json(['success' => true, 'msg' => 'Questions added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }


    //   Student Result see admin
    public function Studentdetails($id)
    {
        $student = User::where('id', $id)->value('name');

        $result = Result::where('user_id', $id)->get();


        // dd($result);
        return view('admin.viewstudentresult', ['students' => $student], ['results' => $result]);
    }

    ////See Question
    public function getExamQuestions(Request $request)
    {
        try {

            $data = QnaExam::where('exam_id', $request->exam_id)->with('question')->get();
            return response()->json(['success' => true, 'msg' => 'Questions deatils!', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    ////See Student
    public function getStudentsee(Request $request)
    {
        try {
            $users = User::select('id', 'email', 'name')->get();

            if ($users->isEmpty()) {
                return response()->json(['success' => false, 'msg' => 'Users not Found!']);
            }

            $data = [];
            $counter = 0;

            foreach ($users as $student) {
                // Here, you can access $student->email and $student->name directly
                $qnaExam = User::where(['id' => $request->exam_id, 'email' => $student->id])->get();

                if ($qnaExam->isEmpty()) {
                    $data[$counter]['id'] = $student->id;
                    $data[$counter]['email'] = $student->email;
                    $data[$counter]['name'] = $student->name; // Add the 'name' column to the data array
                    $counter++;
                }
            }

            return response()->json(['success' => true, 'msg' => 'User data!', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function deleteExamQuestions(Request $request)
    {
        try {

            QnaExam::where('id', $request->id)->delete();
            return response()->json(['success' => true, 'msg' => 'Questions deleted!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function loadMarks()
    {
        $exams = Exam::with('getQnaExam')->get();
        return view('admin.marksDashboard', compact('exams'));
    }

    public function updateMarks(Request $request)
    {
        try {

            Exam::where('id', $request->exam_id)->update([
                'marks' => $request->marks,
                'pass_marks' => $request->pass_marks
            ]);
            return response()->json(['success' => true, 'msg' => 'Marks Updated!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function reviewExams()
    {
        $attempts = ExamAttempt::with(['user', 'exam'])->orderBy('id')->get();

        return view('admin.review-exams', compact('attempts'));
    }

    public function reviewQna(Request $request)
    {
        try {

            $attemptData = ExamAnswer::where('attempt_id', $request->attempt_id)->with(['question', 'answers'])->get();

            return response()->json(['success' => true, 'data' => $attemptData]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function approvedQna(Request $request)
    {
        try {

            $attemptId = $request->attempt_id;

            $examData = ExamAttempt::where('id', $attemptId)->with(['user', 'exam'])->get();
            $marks = $examData[0]['exam']['marks'];

            $attemptData = ExamAnswer::where('attempt_id', $attemptId)->with('answers')->get();

            $totalMarks = 0;

            if (count($attemptData) > 0) {

                foreach ($attemptData as $attempt) {

                    if ($attempt->answers->is_correct == 1) {
                        $totalMarks += $marks;
                    }
                }
            }

            ExamAttempt::where('id', $attemptId)->update([
                'status' => 1,
                'marks'  => $totalMarks
            ]);

            $url = URL::to('/');

            $data['url'] = $url . '/results';
            $data['name'] = $examData[0]['user']['name'];
            $data['email'] = $examData[0]['user']['email'];
            $data['exam_name'] = $examData[0]['exam']['exam_name'];
            $data['title'] = $examData[0]['exam']['exam_name'] . ' Result';

            Mail::send('result-mail', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });

            return response()->json(['success' => true, 'msg' => 'Approved Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function loadPackageDashboard()
    {
        $exams = Exam::where('plan', 0)->get();
        $packages = Package::orderBy('created_at', 'DESC')->get();

        return view('admin.packageDashboard', compact(['exams', 'packages']));
    }

    public function addPackage(Request $request)
    {
        $exmIds = [];
        foreach ($request->exams as $exam) {
            $exmIds[] = (int)$exam;
        }

        $price = json_encode(
            [
                'INR' => $request->price_inr,
                'USD' => $request->price_usd
            ]
        );

        Package::insert([
            'name' => $request->package_name,
            'exam_id' => json_encode($exmIds),
            'price' => $price,
            'expire' => $request->expire
        ]);
        return redirect()->back();
    }

    public function deletePackage(Request $request)
    {
        try {

            Package::where('id', $request->id)->delete();

            return response()->json(['success' => true, 'msg' => 'Package deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function editPackage(Request $request)
    {
        $exmIds = [];
        foreach ($request->exams as $exam) {
            $exmIds[] = (int)$exam;
        }

        $price = json_encode(
            [
                'INR' => $request->price_inr,
                'USD' => $request->price_usd
            ]
        );

        Package::where('id', $request->package_id)->update([
            'name' => $request->package_name,
            'exam_id' => json_encode($exmIds),
            'price' => $price,
            'expire' => $request->expire
        ]);
        return redirect()->back();
    }

    public function paymentDetails()
    {
        $paymentDetails = ExamPayments::with(['exam', 'user'])->orderBy('id', 'DESC')->get();
        return view('admin.payment-details', compact('paymentDetails'));
    }
    public function studentquery()
    {
        $studentQueries = StudentQuery::all();

        return view('admin.viewstudentquery', compact('studentQueries'));
    }

    //delete Query
    public function deleteqery(Request $request)
    {
        try {

            StudentQuery::where('id', $request->id)->delete();
            return response()->json(['success' => true, 'msg' => 'Subject deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        };
    }
}
