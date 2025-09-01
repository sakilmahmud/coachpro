<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\QnaExam;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use App\Models\ExamPayments;
use App\Models\Package;
use App\Models\MockTest;
use App\Models\Question;
use App\Models\Result;
use App\Models\Access;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

use Razorpay\Api\Api;

class StudentController extends Controller
{
    //
    public function paidExamDashboard()
    {
        $exams = Exam::where('plan', 1)->with(['subjects', 'getPaidInformation'])->orderBy('date', 'DESC')->get();
        return view('student.paid-exmas', ['exams' => $exams]);
    }

    public function paymentInr(Request $request)
    {

        try {

            $api = new Api(env('PAYMENT_KEY'), env('PAYMENT_SECRET'));

            $orderData = [
                'receipt'         => 'rcptid_11',
                'amount'          => $request->price . '00',
                'currency'        => 'INR'
            ];

            $razorpayOrder = $api->order->create($orderData);

            return response()->json(['success' => true, 'order_id' => $razorpayOrder['id']]);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function verifyPayment(Request $request)
    {

        try {

            $api = new Api(env('PAYMENT_KEY'), env('PAYMENT_SECRET'));

            $attributes = array(
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            );

            $api->utility->verifyPaymentSignature($attributes);

            ExamPayments::insert([
                'exam_id' => $request->exam_id,
                'user_id' => auth()->user()->id,
                'payment_details' => json_encode($attributes)
            ]);

            return response()->json(['success' => true, 'msg' => 'Your payment was successful, Your payment ID ' . $request->razorpay_payment_id]);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'msg' => 'Your payment failed']);
        }
    }

    public function paymentStatus(Request $request, $examid)
    {
        if ($request->PayerID) {

            $data = array(
                'PayerID' => $request->PayerID
            );

            ExamPayments::insert([
                'exam_id' => $examid,
                'user_id' => auth()->user()->id,
                'payment_details' => json_encode($data)
            ]);

            $message = 'Your payment has been done';

            return view('paymentUSD', compact('message'));
        } else {
            $message = 'Your payment failed!';

            return view('paymentUSD', compact('message'));
        }
    }

    public function paidPackagePlans()
    {
        $packages = Package::orderBy('created_at', 'desc')->get();
        return view('student.paidPackagePlans', compact('packages'));
    }

    public function packagePaymentInr(Request $request)
    {

        try {

            $api = new Api(env('PAYMENT_KEY'), env('PAYMENT_SECRET'));

            $orderData = [
                'receipt'         => 'rcptid_11',
                'amount'          => $request->price . '00',
                'currency'        => 'INR'
            ];

            $razorpayOrder = $api->order->create($orderData);

            return response()->json(['success' => true, 'order_id' => $razorpayOrder['id']]);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function verifyPackagePayment(Request $request)
    {

        try {

            $api = new Api(env('PAYMENT_KEY'), env('PAYMENT_SECRET'));

            $attributes = array(
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            );

            $api->utility->verifyPaymentSignature($attributes);

            $packageData = Package::where('id', $request->package_id)->get();

            $exams = $packageData[0]['exam_id'];

            foreach ($exams as $exam) {
                ExamPayments::insert([
                    'exam_id' => $exam->id,
                    'user_id' => auth()->user()->id,
                    'payment_details' => json_encode($attributes)
                ]);
            }

            return response()->json(['success' => true, 'msg' => 'Your payment was successful, Your payment ID ' . $request->razorpay_payment_id]);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'msg' => 'Your payment failed']);
        }
    }

    public function packagePaymentStatus(Request $request, $packageid)
    {
        if ($request->PayerID) {

            $data = array(
                'PayerID' => $request->PayerID
            );

            $packageData = Package::where('id', $packageid)->get();

            $exams = $packageData[0]['exam_id'];

            foreach ($exams as $exam) {
                ExamPayments::insert([
                    'exam_id' => $exam->id,
                    'user_id' => auth()->user()->id,
                    'payment_details' => json_encode($data)
                ]);
            }

            $message = 'Your payment has been done';

            return view('packagePaymentUSD', compact('message'));
        } else {
            $message = 'Your payment failed!';

            return view('packagePaymentUSD', compact('message'));
        }
    }

    public function submitMockTestResult(Request $request)
    {
        $request->validate([
            'mock_test_id' => 'required|exists:mock_tests,id',
            'correct_count' => 'required|integer',
            'incorrect_count' => 'required|integer',
            'unattempted_count' => 'required|integer',
            'percentage' => 'required|numeric',
        ]);

        $mockTest = MockTest::find($request->mock_test_id);
        if (!$mockTest) {
            return response()->json(['success' => false, 'message' => 'Mock test not found.'], 404);
        }

        Result::create([
            'user_id' => Auth::id(),
            'course_id' => $mockTest->course_id, // Assuming mock_tests table has course_id
            'mock_test_id' => $request->mock_test_id,
            'percentage' => $request->percentage,
            'correct_count' => $request->correct_count,
            'incorrect_count' => $request->incorrect_count,
            'unattempted_count' => $request->unattempted_count,
        ]);

        // Redirect to a results page or return success
        return response()->json([
            'success' => true,
            'message' => 'Results saved successfully!',
            'redirect_url' => route('student.mock.test.result', $request->mock_test_id) // Assuming you have a route for displaying results
        ]);
    }

    public function mockTestResult($mock_test_id)
    {
        $mockTest = MockTest::with('course')->find($mock_test_id);
        if (!$mockTest) {
            abort(404, 'Mock Test not found.');
        }

        $latestResult = Result::where('user_id', Auth::id())
            ->where('mock_test_id', $mock_test_id)
            ->orderBy('created_at', 'desc')
            ->first(); // Get only the latest result

        if (!$latestResult) { // Check if a result exists
            return view('student.mock-test-result', compact('mockTest'))->with('results', collect()); // Pass an empty collection if no results
        }

        return view('student.mock-test-result', compact('mockTest', 'latestResult')); // Pass only the latest result
    }

    public function studentCourses()
    {
        $studentId = Auth::id();

        // Get all Access records for the student, eager loading subject and its course and mockTests
        $accessRecords = Access::where('student_id', $studentId)
                            ->with('subject.course.mockTests')
                            ->get();

        // Initialize enrolledBatches as an empty collection
        $enrolledBatches = collect();

        // Iterate through each access record to build enrolledBatches
        foreach ($accessRecords as $access) {
            $subject = $access->subject;

            // Ensure subject is not null before processing
            if ($subject) {
                $enrolledBatches->push((object)[
                    'titel' => $subject->titel,
                    'course_id' => $subject->course_id,
                    'course' => $subject->course,
                    'mockTests' => $subject->course->mockTests ?? collect(),
                ]);
            }
        }

        // Get all available courses
        $allCourses = \App\Models\Course::all();

        return view('student.courses', compact('allCourses', 'enrolledBatches'));
    }

    public function mockTests($course_id = null)
    {
        $studentId = Auth::id();
        $subjectIds = Access::where('student_id', $studentId)->pluck('subject_id');
        $courseIds = Subject::whereIn('id', $subjectIds)->pluck('course_id')->unique();

        if ($course_id) {
            $mockTests = MockTest::with('course')->where('course_id', $course_id)->whereIn('course_id', $courseIds)->get();
        } else {
            $mockTests = MockTest::with('course')->whereIn('course_id', $courseIds)->get();
        }

        return view('student.mock-tests', compact('mockTests'));
    }

    public function mockTestQuestions($id)
    {
        $mockTest = MockTest::with('questions.answers', 'course')->find($id);

        if (!$mockTest) {
            abort(404);
        }

        $questions = [];
        $answers = [];
        $correctAnswers = [];

        foreach ($mockTest->questions as $question) {
            $questions[] = [
                'id' => $question->id,
                'question' => $question->question,
                'image' => $question->image,
            ];

            $questionAnswers = [];
            $correctAnswerIds = [];
            foreach ($question->answers as $answer) {
                $questionAnswers[] = [
                    'id' => $answer->id,
                    'answer' => $answer->answer,
                ];
                if ($answer->is_correct) {
                    $correctAnswerIds[] = $answer->id;
                }
            }
            $answers[] = $questionAnswers;
            $correctAnswers[] = $correctAnswerIds;
        }

        return view('student.mock-test-questions', compact('mockTest', 'questions', 'answers', 'correctAnswers'));
    }

    public function attemptedMockTests()
    {
        $allAttempts = Result::where('user_id', Auth::id())
            ->with('mockTest.course') // Eager load the associated MockTest for each result
            ->orderBy('created_at', 'desc') // Order by latest attempt
            ->get();

        return view('student.attempted-mock-tests', compact('allAttempts'));
    }

    public function profile()
    {
        $student = Auth::user();
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'string|required|min:2',
            'phone_no' => 'string|required',
            'country' => 'string|required',
            'address' => 'string|required',
            'city' => 'string|required',
            'state' => 'string|required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $student = Auth::user();
        $student->name = $request->name;
        $student->phone_no = $request->phone_no;
        $student->altphone_no = $request->altphone_no;
        $student->country = $request->country;
        $student->address = $request->address;
        $student->address_2 = $request->address_2;
        $student->city = $request->city;
        $student->state = $request->state;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $student->image = $imageName;
        }

        $student->save();

        return back()->with('success', 'Your profile has been updated successfully.');
    }

    public function changePassword()
    {
        return view('student.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password does not match!');
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password successfully changed!');
    }
}
