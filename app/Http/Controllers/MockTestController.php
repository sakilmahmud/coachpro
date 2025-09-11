<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class MockTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::withCount('mockTests')->get();
        return view('admin.mock_tests.courses', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $courseId = $request->query('course_id');
        $course = \App\Models\Course::findOrFail($courseId);
        return view('admin.mock_tests.create', compact('course'));
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'time' => 'required|integer|min:1',
        ]);

        \App\Models\MockTest::create($request->all());

        return redirect()->route('mock-tests.show', $request->course_id)->with('success', 'Mock Test created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = \App\Models\Course::findOrFail($id);
        $mockTests = \App\Models\MockTest::where('course_id', $id)->withCount('questions')->get();
        return view('admin.mock_tests.index', compact('course', 'mockTests'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mockTest = \App\Models\MockTest::findOrFail($id);
        return view('admin.mock_tests.edit', compact('mockTest'));
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'time' => 'required|integer|min:1',
        ]);

        $mockTest = \App\Models\MockTest::findOrFail($id);
        $mockTest->update($request->all());

        return redirect()->route('mock-tests.show', $mockTest->course_id)->with('success', 'Mock Test updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mockTest = \App\Models\MockTest::findOrFail($id);
        $courseId = $mockTest->course_id;
        $mockTest->delete();

        return redirect()->route('mock-tests.show', $courseId)->with('success', 'Mock Test deleted successfully.');
    }
}
