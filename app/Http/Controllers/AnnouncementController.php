<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the announcements.
     */
    public function index(): View|Factory
    {
        $announcements = Announcement::query()
            ->with('author')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create(): View|Factory
    {
        $announcement = new Announcement([
            'is_published' => true,
            'type' => 'announcement',
        ]);

        return view('admin.announcements.create', compact('announcement'));
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['created_by'] = $request->user()->id;

        Announcement::create($data);

        return redirect()
            ->route('announcements.index')
            ->with('success', 'تم نشر الإعلان بنجاح.');
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement): View|Factory
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $data = $this->validatedData($request, $announcement);

        $announcement->update($data);

        return redirect()
            ->route('announcements.index')
            ->with('success', 'تم تحديث الإعلان بنجاح.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()
            ->route('announcements.index')
            ->with('success', 'تم حذف الإعلان بنجاح.');
    }

    /**
     * Validate the announcement request data.
     */
    protected function validatedData(Request $request, ?Announcement $announcement = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'type' => ['required', 'string', 'in:announcement,news'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:published_at'],
        ]);

        $data['is_published'] = $request->boolean('is_published');

        if ($data['is_published']) {
            $data['published_at'] = $data['published_at'] ?: now();
        } else {
            $data['published_at'] = $data['published_at'] ?? null;
        }

        return $data;
    }
}
