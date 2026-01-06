<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guide;
use Illuminate\Support\Str;
use App\Models\GuideResource; 

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

        $guideData['issue_slug'] = Str::slug($request->issue);
        
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

    public function edit(Guide $guide)
    {
        return view('admin.guides.edit', compact('guide'));
    }

    public function update(Request $request, Guide $guide)
    {
       $guide->update([
            'device'     => $request->device,
            'category'   => $request->category,
            'brand'      => $request->brand,
            'series'     => $request->series,
            'model'      => $request->model,
            'issue'      => $request->issue,
            'issue_slug' => \Str::slug($request->issue),
        ]);


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

    public function devices()
    {
        $devices = Guide::select('device')->distinct()->orderBy('device')->get();

        return view('pages.guides.devices', compact('devices'));
    }

    public function categories($device)
    {
        $categories = Guide::where('device', $device)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->get();

        return view('pages.guides.categories', compact('device', 'categories'));
    }

    public function issues($device, $category)
    {
        $issues = Guide::where('device', $device)
            ->where('category', $category)
            ->select('issue', 'issue_slug')
            ->distinct()
            ->orderBy('issue')
            ->get();

        return view('pages.guides.issues', compact('device', 'category', 'issues'));
    }

     public function show(Guide $guide)
    {
        // Ensure slug exists (runtime safety)
        $issueSlug = $guide->issue_slug ?: Str::slug($guide->issue);

        $seo = [
            'title'       => $guide->issue . ' Fix Guide',
            'description' => "Troubleshooting guide for {$guide->device} {$guide->model} {$guide->issue}.",
            'image'       => asset('storage/guides/fixing.png'),
            'url' => route('guides.show', [
                'device'   => $guide->device,
                'category' => $guide->category,
                'issue'    => $issueSlug,
            ]),
            'type'        => 'article'
        ];

        return view('admin.guides.show', compact('guide','seo'));
    }


    // PUBLIC guide reading page
    public function showU($device, $category, $issue)
    {
        // 1️⃣ Try resolving by slug first
        $guide = Guide::with(['resources', 'reviews.user'])
            ->where('device', $device)
            ->where('category', $category)
            ->where('issue_slug', $issue)
            ->first();

        // 2️⃣ Fallback: issue_slug is NULL → match against issue text
        if (!$guide) {
            $guide = Guide::with(['resources', 'reviews.user'])
                ->where('device', $device)
                ->where('category', $category)
                ->whereRaw('LOWER(issue) = ?', [str_replace('-', ' ', strtolower($issue))])
                ->firstOrFail();

            // 3️⃣ Generate slug if missing
            if (!$guide->issue_slug) {
                $guide->issue_slug = Str::slug($guide->issue);
                $guide->saveQuietly();
            }

            // 4️⃣ Redirect to canonical slug URL (SEO-safe)
            return redirect()->route('guides.show', [
                'device'   => $guide->device,
                'category' => $guide->category,
                'issue'    => $guide->issue_slug,
            ], 301);
        }

        // 5️⃣ Normal render path
        $seo = [
            'title'       => "{$guide->device} - {$guide->issue} Troubleshooting Guide",
            'description' => "Learn how to fix {$guide->issue} on {$guide->device} under {$guide->category}.",
            'image'       => asset('storage/guides/fixing.png'),
            'url'         => url()->current(),
            'type'        => 'article',
        ];

        return view('pages.guides.show', compact(
            'device',
            'category',
            'issue',
            'guide',
            'seo'
        ));
    }




    public function storeReview(Request $request, Guide $guide)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        $guide->reviews()->create([
            'rating'   => $request->rating,
            'review'   => $request->review,
            'user_id'  => auth()->id(),
        ]);

        return back()->with('success', 'Review submitted successfully.');
    }


}