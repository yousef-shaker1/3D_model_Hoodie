<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\LogoSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoSectionController extends Controller
{
     public function index()
    {
        $sections = LogoSection::latest()->paginate(10);
        return view('dashboard.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('dashboard.sections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        $logoPath = $request->file('logo')->store('logos', 'public');

        LogoSection::create([
            'name' => $request->name,
            'logo' => $logoPath,
        ]);

        return redirect()->route('sections.index')->with('success', 'تم إضافة القسم بنجاح');
    }

    public function edit(LogoSection $section)
    {
        return view('dashboard.sections.edit', compact('section'));
    }

    public function update(Request $request, LogoSection $section)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($section->logo);
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $section->update($data);

        return redirect()->route('sections.index')->with('success', 'تم تحديث القسم بنجاح');
    }

    public function destroy(LogoSection $section)
    {
        Storage::disk('public')->delete($section->logo);
        $section->delete();
        return redirect()->route('sections.index')->with('success', 'تم حذف القسم بنجاح');
    }
}
