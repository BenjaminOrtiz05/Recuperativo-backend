<?php

// app/Http/Controllers/CourseController.php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('query');      // parámetro ?query=
        $category = $request->query('category'); // parámetro ?category=

        $courses = Course::query();

        if ($query) {
            $courses->where('name', 'like', "%{$query}%");
        }

        if ($category && $category !== 'all') {
            $courses->where('category', $category);
        }

        return $courses->get();
    }

    public function show($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }
        return $course;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'duration' => 'required|string',
            'active' => 'required|boolean',
            'image' => 'nullable|string',
        ]);

        $course = Course::create($validated);
        return response()->json($course, 201);
    }

    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        $course->update($request->all());
        return $course;
    }

    public function destroy($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        $course->delete();
        return response()->json(['message' => 'Curso eliminado']);
    }
}
