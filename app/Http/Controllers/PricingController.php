<?php

namespace App\Http\Controllers;

use App\Models\PricingSetting;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $settings = PricingSetting::current();
        return view('dashboard.pricing.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tshirt_base_price'          => 'required|numeric|min:0',
            'dtf_price_per_meter'        => 'nullable|numeric|min:1',
            'frame_width_cm'             => 'required|numeric|min:10|max:300',
            'profit_margin_percent'      => 'required|numeric|min:0|max:1000',
            'min_print_price'            => 'required|numeric|min:0',
            'print_area_width_cm'        => 'required|numeric|min:1|max:100',
            'print_area_height_cm'       => 'required|numeric|min:1|max:100',
            'max_width_cm'               => 'required|numeric|min:1|max:100',
            'max_height_cm'              => 'required|numeric|min:1|max:100',
            'max_text_width_cm'          => 'required|numeric|min:1|max:100',
            'max_text_height_cm'         => 'required|numeric|min:1|max:100',
        ]);

        $setting = PricingSetting::first() ?? new PricingSetting();
        $setting->fill([
            'tshirt_base_price'          => $request->tshirt_base_price,
            'dtf_price_per_meter'        => $request->dtf_price_per_meter,
            'frame_width_cm'             => $request->frame_width_cm,
            'profit_margin_percent'      => $request->profit_margin_percent,
            'min_print_price'            => $request->min_print_price,
            'print_area_width_cm'        => $request->print_area_width_cm,
            'print_area_height_cm'       => $request->print_area_height_cm,
            'max_width_cm'               => $request->max_width_cm,
            'max_height_cm'              => $request->max_height_cm,
            'max_text_width_cm'          => $request->max_text_width_cm,
            'max_text_height_cm'         => $request->max_text_height_cm,
        ])->save();

        return redirect()->route('pricing.index')
            ->with('success', 'تم حفظ إعدادات التسعير بنجاح ✓');
    }

    public function getSettings()
    {
        return response()->json(PricingSetting::current());
    }
}
