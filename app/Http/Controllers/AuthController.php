<?php

namespace App\Http\Controllers;

use App\Models\Access;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\MockTest;
use App\Models\Result;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\PasswordReset;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    //
    public function loadRegister()
    {
        if (Auth::user() && Auth::user()->is_admin == 1) {
            return redirect('/admin/dashboard');
        } else if (Auth::user() && Auth::user()->is_admin == 0) {
            return redirect('/dashboard');
        }
        return view('register');
    }

    public function studentRegister(Request $request)
    {
        $request->validate([
            'name' => 'string|required|min:2',
            'email' => 'string|email|required|max:100|unique:users',
            'password' => 'string|required|min:6',
            'phone_no' => 'string|required',
            'country' => 'string|required',
            'address' => 'string|required',
            'city' => 'string|required',
            'state' => 'string|required',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_no = $request->phone_no;
        $user->altphone_no = $request->altphone_no;
        $user->country = $request->country;
        $user->address = $request->address;
        $user->address_2 = $request->address_2;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->save();

        return back()->with('success', 'Your Registration has been successfull.');
    }

    public function loadLogin()
    {
        if (Auth::user() && Auth::user()->is_admin == 1) {
            return redirect('/admin/dashboard');
        } else if (Auth::user() && Auth::user()->is_admin == 0) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'string|required|email',
            'password' => 'string|required'
        ]);

        $userCredential = $request->only('email', 'password');
        if (Auth::attempt($userCredential)) {

            if (Auth::user()->is_admin == 1) {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/dashboard');
            }
        } else {
            return back()->with('error', 'Username & Password is incorrect');
        }
    }

    public function loadDashboard()
    {
        $user = Auth::user();

        // Get all Access records for the student, eager loading subject and its course and mockTests
        $accessRecords = Access::where('student_id', $user->id)
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

        // Calculate total mock tests based on enrolled batches
        $totalMockTests = 0;
        foreach ($enrolledBatches as $batch) {
            $totalMockTests += count($batch->mockTests);
        }

        // Calculate enrolled batches count
        $enrolledBatchesCount = $enrolledBatches->count(); // Add this line

        // Attempted Mock Tests
        $attemptedMockTestCount = Result::where('user_id', $user->id)
                                        ->distinct('mock_test_id')
                                        ->count();

        // Average Score
        $averageScore = Result::where('user_id', $user->id)->avg('percentage');
        $averageScore = round($averageScore ?? 0, 2); // Handle null if no results

        return view('student.dashboard', compact('totalMockTests', 'attemptedMockTestCount', 'averageScore', 'enrolledBatches', 'enrolledBatchesCount')); // Pass enrolledBatchesCount
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

        $user = Auth::user();
        $user->name = $request->name;
        $user->phone_no = $request->phone_no;
        $user->altphone_no = $request->altphone_no;
        $user->country = $request->country;
        $user->address = $request->address;
        $user->address_2 = $request->address_2;
        $user->city = $request->city;
        $user->state = $request->state;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $user->image = $imageName;
        }

        $user->save();

        return back()->with('success', 'Your profile has been updated successfully.');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }

    public function forgetPasswordLoad()
    {
        return view('forget-password');
    }

    public function forgetPassword(Request $request)
    {
        try {

            $user = User::where('email', $request->email)->get();

            if (count($user) > 0) {

                $token = Str::random(40);
                $domain = URL::to('/');
                $url = $domain . '/reset-password?token=' . $token;

                $data['url'] = $url;
                $data['email'] = $request->email;
                $data['title'] = 'Password Reset';
                $data['body'] = 'Please click on below link to reset your password.';
                //dd( $url);
                Mail::send('forgetPasswordMail', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });

                $dateTime = Carbon::now()->format('Y-m-d H:i:s');

                PasswordReset::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'email' => $request->email,
                        'token' => $token,
                        'created_at' => $dateTime
                    ]
                );

                return back()->with('success', 'Please check your mail to reset your password!');
            } else {
                return back()->with('error', 'Email is not exists!');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function resetPasswordLoad(Request $request)
    {
        $resetData = PasswordReset::where('token', $request->token)->get();

        if (isset($request->token) && count($resetData) > 0) {

            $user = User::where('email', $resetData[0]['email'])->get();

            return view('resetPassword', compact('user'));
        } else {
            return view('404');
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::find($request->id);
        $user->password = Hash::make($request->password);
        $user->save();

        PasswordReset::where('email', $user->email)->delete();

        return "<h2>Your password has been reset successfully.</h2>";
    }
}
