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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/courses'), $logoName);
            $data['logo'] = $logoName;
        }

        Course::create($data);

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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $course = Course::find($id);
        $data = $request->all();

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/courses'), $logoName);
            $data['logo'] = $logoName;

            // Delete old logo if it exists
            if ($course->logo) {
                $oldLogoPath = public_path('uploads/courses/' . $course->logo);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
            }
        }

        $course->update($data);

        return response()->json(['success' => true, 'message' => 'Course updated successfully!']);
    }

    public function destroy($id)
    {
        Course::destroy($id);
        return response()->json(['success' => true, 'message' => 'Course deleted successfully!']);
    }
}
