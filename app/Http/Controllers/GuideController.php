<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;

class GuideController extends Controller
{
    public function index()
    {
        $guides = Guide::latest()->paginate(10);
        return view('admin.guides.index', compact('guides'));
    }

    public function create()
    {
        return view('admin.guides.create');
    }

    public function store(Request $request)
    {
        $guide = Guide::create($request->only([
            'device', 'category', 'brand', 'series', 'model', 'issue'
        ]));

        foreach ($request->resources as $res) {
            $guide->resources()->create($res);
        }

        return redirect()->route('admin.guides.index');
    }

    public function show(Guide $guide)
    {
        return view('admin.guides.show', compact('guide'));
    }

    public function edit(Guide $guide)
    {
        return view('admin.guides.edit', compact('guide'));
    }

    public function update(Request $request, Guide $guide)
    {
        $guide->update($request->only([
            'device','category','brand','series','model','issue'
        ]));

        $guide->resources()->delete();
        foreach ($request->resources as $res) {
            $guide->resources()->create($res);
        }

        return redirect()->route('admin.guides.index');
    }
}

