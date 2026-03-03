<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Classroom;

class ClassController extends Controller
{
    public function create()
    {
        return view('lecturer.classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $response = Http::timeout(5)
            ->acceptJson()
            ->post(
                config('services.generalclass.url') . '/api/classes/start',
                [
                    'lecturer_id' => auth()->id(),
                ]
            );

        if (!$response->successful()) {
            Log::error('GeneralClass unreachable', [
                'status' => $response->status(),
            ]);

            return back()->withErrors([
                'general' => 'Class server is unavailable.'
            ]);
        }

        $data = $response->json();

        if (($data['status'] ?? null) !== 'created' || empty($data['uuid'])) {
            Log::warning('Invalid GeneralClass response', [
                'user_id' => auth()->id(),
                'response' => $data,
            ]);

            return back()->withErrors([
                'general' => 'Class server is not ready.'
            ]);
        }

        Log::info('GeneralClass class started', [
            'user_id' => auth()->id(),
            'uuid' => $data['uuid'],
        ]);

        $class = Classroom::create([
            'lecturer_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'uuid' => $data['uuid'],
            'is_active' => true,
        ]);

        return redirect()->route('lecturer.classes.show', $class);
    }
    
    public function show(Classroom $classroom)
    {
        // Safety: ensure lecturer owns this class
        abort_unless($classroom->lecturer_id === auth()->id(), 403);

        return view('lecturer.classes.show', [
            'classroom' => $classroom,
            'classUuid' => $classroom->uuid,
            'wsUrl' => config('services.generalclass.url'),
        ]);
    }

    public function end(Classroom $class)
    {
        if ($class->lecturer_id !== auth()->id()) {
            abort(403);
        }

        if (!$class->is_active) {
            return back()->withErrors([
                'general' => 'Class is already ended.'
            ]);
        }

        $response = Http::timeout(5)
            ->acceptJson()
            ->post(
                config('services.generalclass.url') . '/api/classes/stop',
                [
                    'uuid' => $class->uuid,
                ]
            );

        if (!$response->successful()) {
            Log::error('Failed to stop class on GeneralClass', [
                'uuid' => $class->uuid,
                'status' => $response->status(),
            ]);

            return back()->withErrors([
                'general' => 'Failed to stop class server.'
            ]);
        }

        $class->update([
            'is_active' => false,
        ]);

        Log::info('Class ended successfully', [
            'user_id' => auth()->id(),
            'uuid' => $class->uuid,
        ]);

        return back()->with('success', 'Class ended successfully.');
    }

    public function restart(Classroom $class)
    {
        if ($class->lecturer_id !== auth()->id()) {
            abort(403);
        }

        if ($class->is_active) {
            return back()->withErrors([
                'general' => 'Class is already active.'
            ]);
        }

        $response = Http::timeout(5)
            ->acceptJson()
            ->post(
                config('services.generalclass.url') . '/api/classes/start',
                [
                    'lecturer_id' => auth()->id(),
                ]
            );

        if (!$response->successful()) {
            return back()->withErrors([
                'general' => 'Failed to restart class server.'
            ]);
        }

        $data = $response->json();

        $class->update([
            'uuid' => $data['uuid'], // new session UUID
            'is_active' => true,
        ]);

        return redirect()->route('lecturer.classes.show', $class);
    }

    public function destroy(Classroom $class)
    {
        if ($class->lecturer_id !== auth()->id()) {
            abort(403);
        }

        // If active, stop it first
        if ($class->is_active) {
            Http::timeout(5)
                ->post(config('services.generalclass.url') . '/api/classes/stop', [
                    'uuid' => $class->uuid,
                ]);
        }

        $class->delete();

        return back()->with('success', 'Class deleted successfully.');
    }

}

