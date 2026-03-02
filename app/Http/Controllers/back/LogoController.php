<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\LogoSection;
use App\Models\logo as LogoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoController extends Controller
{
     public function index()
    {
        $logos = LogoModel::with('section')->latest()->paginate(10);
        return view('dashboard.logos.index', compact('logos'));
    }

    public function create()
    {
        $sections = LogoSection::all();
        return view('dashboard.logos.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'logo_section_id' => 'required|exists:logo_sections,id',
            'image'           => 'required|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        $imagePath = $request->file('image')->store('logos/images', 'public');

        LogoModel::create([
            'logo_section_id' => $request->logo_section_id,
            'image'           => $imagePath,
        ]);

        return redirect()->route('logos.index')->with('success', 'تم إضافة اللوجو بنجاح');
    }

    public function edit(LogoModel $logo)
    {
        $sections = LogoSection::all();
        return view('dashboard.logos.edit', compact('logo', 'sections'));
    }

    public function update(Request $request, LogoModel $logo)
    {
        $request->validate([
            'logo_section_id' => 'required|exists:logo_sections,id',
            'image'           => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        $data = ['logo_section_id' => $request->logo_section_id];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($logo->image);
            $data['image'] = $request->file('image')->store('logos/images', 'public');
        }

        $logo->update($data);

        return redirect()->route('logos.index')->with('success', 'تم تحديث اللوجو بنجاح');
    }

    public function destroy(LogoModel $logo)
    {
        Storage::disk('public')->delete($logo->image);
        $logo->delete();
        return redirect()->route('logos.index')->with('success', 'تم حذف اللوجو بنجاح');
    }
}


