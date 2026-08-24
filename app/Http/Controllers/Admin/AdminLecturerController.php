<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminLecturerController extends Controller
{
    /**
     * Display all lecturers with search and filters.
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->where('is_lecturer', true)
            ->where('is_admin', false);

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by approval status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approve', true);
            }

            if ($request->status === 'pending') {
                $query->where('is_approve', false);
            }
        }

        $lecturers = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.lecturers.index', compact('lecturers'));
    }

    /**
     * Display lecturers awaiting approval.
     */
    public function pending()
    {
        $lecturers = User::query()
            ->where('is_lecturer', true)
            ->where('is_approve', false)
            ->where('is_admin', false)
            ->latest()
            ->paginate(15);
        return view('admin.lecturers.pending', compact('lecturers'));
    }

    /**
     * Display a specific lecturer's information.
     */
    public function show(User $lecturer)
    {
        // Prevent non-lecturers from being viewed as lecturers.
        abort_unless($lecturer->is_lecturer, 404);

        return view('admin.lecturers.show', compact('lecturer'));
    }

    /**
     * Approve a lecturer.
     */
    public function approve(User $lecturer)
    {
        abort_unless($lecturer->is_lecturer, 404);

        if ($lecturer->is_approve) {
            return redirect()
                ->route('admin.lecturers.show', $lecturer)
                ->with('info', 'This lecturer is already approved.');
        }

        $lecturer->is_approve = true;
        $lecturer->save();

        return redirect()
            ->route('admin.lecturers.show', $lecturer)
            ->with('success', 'Lecturer approved successfully.');
    }
}