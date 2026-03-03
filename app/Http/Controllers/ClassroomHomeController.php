<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassroomHomeController extends Controller
{
    public function index()
    {
        return view('pages.classroom.home');
    }
}
