<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SectionsController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        if (Auth::user()->role === 'instructor' && $course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title'       => 'required|string|min:2|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $order = $course->sections()->count() + 1;

        $course->sections()->create([
            'title'       => $request->title,
            'description' => $request->description,
            'order'       => $order,
        ]);

        return redirect()->back()->with('success', 'Section added successfully.');
    }

    // ─── Update (تعديل القسم) ───────────────────────────────────────────────────
    public function update(Request $request, Section $section): RedirectResponse
    {
        if (Auth::user()->role === 'instructor' && $section->course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title'       => 'required|string|min:2|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $section->update($request->only('title', 'description'));

        return redirect()->back()->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        if (Auth::user()->role === 'instructor' && $section->course->instructor_id !== Auth::id()) {
            abort(403);
        }

        if ($section->lessons()->count() > 0) {
            return redirect()->back()
                             ->with('error', 'Cannot delete a section that contains lessons. Delete the lessons first.');
        }

        $section->delete();

        return redirect()->back()->with('success', 'Section deleted successfully.');
    }
}
