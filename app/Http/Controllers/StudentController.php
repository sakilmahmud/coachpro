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
        if($request->PayerID){

            $data = array(
                'PayerID' => $request->PayerID 
            );

            $packageData = Package::where('id',$packageid)->get();

            $exams = $packageData[0]['exam_id'];

            foreach($exams as $exam){
                ExamPayments::insert([
                    'exam_id' => $exam->id,
                    'user_id' => auth()->user()->id,
                    'payment_details' => json_encode($data)
                ]);
            }

            $message = 'Your payment has been done';

            return view('packagePaymentUSD',compact('message'));

        }
        else{
            $message = 'Your payment failed!';

            return view('packagePaymentUSD',compact('message'));
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

        $results = Result::where('user_id', Auth::id())
                        ->where('mock_test_id', $mock_test_id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        if ($results->isEmpty()) {
            // If no results, but mock test exists, still show the page with a message
            return view('student.mock-test-result', compact('mockTest', 'results'));
        }

        return view('student.mock-test-result', compact('mockTest', 'results'));
    }

    public function mockTests()
    {
        $mockTests = MockTest::all();
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
}
