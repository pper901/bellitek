<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;

class LecturerController extends Controller
{

    public function index()
    {
        $classes = Classroom::where('lecturer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('lecturer.dashboard', compact('classes'));
    }

}
