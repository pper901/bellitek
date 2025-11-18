<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guide;
use App\Models\GuideResource; // Ensure this is imported

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
        // 1. Filter and create the main Guide record
        $guideData = $request->only([
            'device', 'category', 'brand', 'series', 'model', 'issue'
        ]);
        
        $guide = Guide::create($guideData); 

        // 2. CRITICAL FIX: Check if the 'resources' key exists and is an array 
        // before attempting to loop over it. This prevents the 500 error 
        // if no resource fields were added.
        if ($request->has('resources') && is_array($request->resources)) {
            
            // Define the fields allowed in the GuideResource model's $fillable array
            $resourceFillables = ['cause', 'solution', 'details'];
            
            foreach ($request->resources as $res) {
                
                // Filter the resource data for Mass Assignment protection
                $resourceData = array_intersect_key($res, array_flip($resourceFillables));
                
                $guide->resources()->create($resourceData);
            }
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
        
        if ($request->has('resources') && is_array($request->resources)) {
            $resourceFillables = ['cause', 'solution', 'details'];
            foreach ($request->resources as $res) {
                $resourceData = array_intersect_key($res, array_flip($resourceFillables));
                $guide->resources()->create($resourceData);
            }
        }

        return redirect()->route('admin.guides.index');
    }
}