<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('admin.courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Course::create($request->all());

        return response()->json(['success' => true, 'message' => 'Course added successfully!']);
    }

    public function edit($id)
    {
        $course = Course::find($id);
        return response()->json($course);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course = Course::find($id);
        $course->update($request->all());

        return response()->json(['success' => true, 'message' => 'Course updated successfully!']);
    }

    public function destroy($id)
    {
        Course::destroy($id);
        return response()->json(['success' => true, 'message' => 'Course deleted successfully!']);
    }
}
