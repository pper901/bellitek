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
        // 1. Validate input including youtube_url
        $request->validate([
            'device' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'series' => 'nullable|string|max:255',
            'model' => 'required|string|max:255',
            'issue' => 'required|string|max:255',
            'youtube_url' => 'nullable|url', // <-- NEW
        ]);

        // 2. Filter and create the main Guide record
        $guideData = $request->only([
            'device', 'category', 'brand', 'series', 'model', 'issue', 'youtube_url' // <-- NEW
        ]);

        $guideData['issue_slug'] = Str::slug($request->issue);

        $guide = Guide::create($guideData);

        // 3. Create resources if present
        if ($request->has('resources') && is_array($request->resources)) {
            $resourceFillables = ['cause', 'solution', 'details'];
            foreach ($request->resources as $res) {
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
        $request->validate([
            'device' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'series' => 'nullable|string|max:255',
            'model' => 'required|string|max:255',
            'issue' => 'required|string|max:255',
            'youtube_url' => 'nullable|url', // <-- NEW
        ]);

        $guide->update([
            'device'     => $request->device,
            'category'   => $request->category,
            'brand'      => $request->brand,
            'series'     => $request->series,
            'model'      => $request->model,
            'issue'      => $request->issue,
            'youtube_url'=> $request->youtube_url, // <-- NEW
            'issue_slug' => Str::slug($request->issue),
        ]);

        // Delete old resources and recreate
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
        // 1. Fetch all guides (including ones missing slugs)
        $guides = Guide::where('device', $device)
            ->where('category', $category)
            ->orderBy('issue')
            ->get();

        // 2. Backfill missing slugs safely (in-memory loop, minimal writes)
        foreach ($guides as $guide) {
            if (empty($guide->issue_slug)) {
                $slug = Str::slug($guide->issue);

                // Ensure uniqueness per device + category
                $original = $slug;
                $count = 1;

                while (
                    Guide::where('device', $guide->device)
                        ->where('category', $guide->category)
                        ->where('issue_slug', $slug)
                        ->where('id', '!=', $guide->id)
                        ->exists()
                ) {
                    $slug = "{$original}-{$count}";
                    $count++;
                }

                $guide->updateQuietly([
                    'issue_slug' => $slug,
                ]);
            }
        }

        // 3. Return only clean, slugged data to the view
        $issues = $guides
            ->unique('issue_slug')
            ->map(fn ($g) => (object) [
                'issue'      => $g->issue,
                'issue_slug' => $g->issue_slug,
            ]);

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