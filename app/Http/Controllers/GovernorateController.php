<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $governorates = Governorate::orderBy('name')->get();
        return view('dashboard.governorates.index', compact('governorates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'shipping_price' => 'required|numeric|min:0',
        ]);

        Governorate::create([
            'name' => $request->name,
            'shipping_price' => $request->shipping_price,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'تم إضافة المحافظة بنجاح.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Governorate $governorate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'shipping_price' => 'required|numeric|min:0',
        ]);

        $governorate->update([
            'name' => $request->name,
            'shipping_price' => $request->shipping_price,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'تم تحديث بيانات المحافظة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Governorate $governorate)
    {
        $governorate->delete();
        return redirect()->back()->with('success', 'تم حذف المحافظة بنجاح.');
    }
}
