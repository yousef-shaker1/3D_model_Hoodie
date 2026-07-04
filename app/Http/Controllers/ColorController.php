<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::orderBy('sort_order')->get();
        return view('colors.index', compact('colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hex_code' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        Color::create([
            'name' => $request->name,
            'hex_code' => $request->hex_code,
            'active' => $request->active ?? true,
            'sort_order' => $request->sort_order ?? 0
        ]);

        return redirect()->back()->with('success', 'تم إضافة اللون بنجاح');
    }

    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hex_code' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $color->update([
            'name' => $request->name,
            'hex_code' => $request->hex_code,
            'active' => $request->active ?? true,
            'sort_order' => $request->sort_order ?? 0
        ]);

        return redirect()->back()->with('success', 'تم تحديث اللون بنجاح');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->back()->with('success', 'تم حذف اللون بنجاح');
    }

    public function toggleActive(Color $color)
    {
        $color->update(['active' => !$color->active]);
        return redirect()->back()->with('success', 'تم تغيير حالة اللون بنجاح');
    }
}
