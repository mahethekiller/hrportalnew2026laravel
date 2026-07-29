<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of company & department announcements.
     */
    public function index(Request $request): View
    {
        $query = Announcement::with(['company', 'department']);

        if ($request->filled('type')) {
            $query->where('announcement_type', $request->type);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $announcements = $query->orderBy('announcement_id', 'desc')->paginate(12);
        $companies = Company::orderBy('name', 'asc')->get();

        return view('announcements.index', compact('announcements', 'companies'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create(): View
    {
        if (Gate::denies('edit.announcements')) {
            abort(403, 'Unauthorized operation.');
        }

        $companies = Company::orderBy('name', 'asc')->get();
        $departments = Department::orderBy('department_name', 'asc')->get();

        return view('announcements.create', compact('companies', 'departments'));
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('edit.announcements')) {
            abort(403, 'Unauthorized operation.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'announcement_type' => 'required|string|max:100',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'company_id' => 'required|exists:xin_companies,company_id',
            'department_id' => 'nullable|integer',
            'summary' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $imagePath = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/announcements'), $fileName);
            $imagePath = 'uploads/announcements/' . $fileName;
        }

        Announcement::create([
            'title' => $request->title,
            'announcement_type' => $request->announcement_type,
            'acceptance_message' => $request->acceptance_message ?? '',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'company_id' => $request->company_id,
            'department_id' => $request->department_id ?? 0,
            'published_by' => auth()->user()?->username ?? 'Admin',
            'summary' => $request->summary,
            'description' => $request->description,
            'image' => $imagePath,
            'is_active' => 1,
            'created_at' => date('d-m-Y H:i:s'),
        ]);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement published successfully.');
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement): View
    {
        $announcement->load(['company', 'department']);
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        if (Gate::denies('edit.announcements')) {
            abort(403, 'Unauthorized operation.');
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
