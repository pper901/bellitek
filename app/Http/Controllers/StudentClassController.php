<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentClassController extends Controller
{
    public function join()
    {
        // Fetch only active classes
        $classes = Classroom::where('is_active', true)->latest()->get();

        return view('pages.classroom.student', compact('classes'));
    }

    public function enter(Request $request, string $uuid)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $classroom = Classroom::where('uuid', $uuid)
            ->where('is_active', true)
            ->firstOrFail();

        // ✅ Generate student identity
        $studentId = (string) Str::uuid();

        return view('pages.classroom.class', [
            'classroom'   => $classroom,
            'studentName' => $request->name,
            'studentId'   => $studentId,
            'role'        => 'Student',
        ]);
    }


}
