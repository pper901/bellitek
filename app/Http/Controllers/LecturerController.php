<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Classroom;
use App\Services\GeneralClassServerService;


class LecturerController extends Controller
{

    public function index()
    {
        $classes = Classroom::where('lecturer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('lecturer.dashboard', compact('classes'));
    }

    public function websocketHealth(
        GeneralClassServerService $server
    ) {
        $health = $server->health();

        return response()->json($health);
    }

}
