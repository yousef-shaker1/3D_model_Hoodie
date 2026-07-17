<?php

namespace App\Http\Controllers;

use App\Models\PricingSetting;
use App\Models\PricingTier;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $settings = PricingSetting::current();
        // جيب المستويات مع الـ id للعمليات في اللوحة
        $tiers = PricingTier::ordered();
        return view('dashboard.pricing.index', compact('settings', 'tiers'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tshirt_base_price'            => 'required|numeric|min:0',
            'dtf_price_per_meter'          => 'nullable|numeric|min:1',
            'frame_width_cm'               => 'required|numeric|min:10|max:300',
            'profit_margin_percent'        => 'required|numeric|min:0|max:1000',
            'min_print_price'              => 'required|numeric|min:0',
            'print_area_width_cm'          => 'required|numeric|min:1|max:100',
            'print_area_height_cm'         => 'required|numeric|min:1|max:100',
            'max_width_cm'                 => 'required|numeric|min:1|max:100',
            'max_height_cm'                => 'required|numeric|min:1|max:100',
            'max_text_width_cm'            => 'required|numeric|min:1|max:100',
            'max_text_height_cm'           => 'required|numeric|min:1|max:100',
            'max_image_width_cm'           => 'required|numeric|min:1|max:100',
            'max_image_height_cm'          => 'required|numeric|min:1|max:100',
        ]);

        // ── حفظ الإعدادات العامة ──────────────────────────────────────
        $setting = PricingSetting::first() ?? new PricingSetting();
        $setting->fill([
            'tshirt_base_price'     => $request->tshirt_base_price,
            'dtf_price_per_meter'   => $request->dtf_price_per_meter,
            'frame_width_cm'        => $request->frame_width_cm,
            'profit_margin_percent' => $request->profit_margin_percent,
            'min_print_price'       => $request->min_print_price,
            'print_area_width_cm'   => $request->print_area_width_cm,
            'print_area_height_cm'  => $request->print_area_height_cm,
            'max_width_cm'          => $request->max_width_cm,
            'max_height_cm'         => $request->max_height_cm,
            'max_text_width_cm'     => $request->max_text_width_cm,
            'max_text_height_cm'    => $request->max_text_height_cm,
            'max_image_width_cm'    => $request->max_image_width_cm,
            'max_image_height_cm'   => $request->max_image_height_cm,
        ])->save();

        // ── حفظ مستويات التسعير في جدولها ───────────────────────────
        $submittedTiers = $request->input('pricing_tiers', []);
        $submittedIds   = [];

        foreach ($submittedTiers as $sortOrder => $data) {
            // تجاهل الصفوف الفارغة
            if (empty($data['max_area_cm2']) || empty($data['price'])) {
                continue;
            }

            $values = [
                'max_area_cm2' => (float) $data['max_area_cm2'],
                'price'        => (int)   $data['price'],
                'sort_order'   => (int)   $sortOrder,
            ];

            $id = !empty($data['id']) ? (int) $data['id'] : null;

            if ($id) {
                // تحديث موجود
                $tier = PricingTier::find($id);
                if ($tier) {
                    $tier->update($values);
                    $submittedIds[] = $tier->id;
                }
            } else {
                // إنشاء جديد
                $tier = PricingTier::create($values);
                $submittedIds[] = $tier->id;
            }
        }

        // حذف المستويات التي أُزيلت من الواجهة — فقط لو في tiers اترسلت
        if (!empty($submittedIds)) {
            PricingTier::whereNotIn('id', $submittedIds)->delete();
        }

        return redirect()->route('pricing.index')
            ->with('success', 'تم حفظ إعدادات التسعير بنجاح ✓');
    }

    public function updateTiers(Request $request)
    {
        $request->validate([
            'pricing_tiers'                => 'nullable|array',
            'pricing_tiers.*.id'           => 'nullable|integer|exists:pricing_tiers,id',
            'pricing_tiers.*.max_area_cm2' => 'nullable|numeric|min:1',
            'pricing_tiers.*.price'        => 'nullable|numeric|min:1',
        ]);

        $submittedTiers = $request->input('pricing_tiers', []);
        $submittedIds   = [];

        foreach ($submittedTiers as $sortOrder => $data) {
            if (empty($data['max_area_cm2']) || empty($data['price'])) {
                continue;
            }

            $values = [
                'max_area_cm2' => (float) $data['max_area_cm2'],
                'price'        => (int)   $data['price'],
                'sort_order'   => (int)   $sortOrder,
            ];

            $id = !empty($data['id']) ? (int) $data['id'] : null;

            if ($id) {
                $tier = PricingTier::find($id);
                if ($tier) {
                    $tier->update($values);
                    $submittedIds[] = $tier->id;
                }
            } else {
                $tier = PricingTier::create($values);
                $submittedIds[] = $tier->id;
            }
        }

        if (!empty($submittedIds)) {
            PricingTier::whereNotIn('id', $submittedIds)->delete();
        }

        return redirect()->route('pricing.index')
            ->with('success_tiers', 'تم حفظ مستويات التسعير بنجاح ✓');
    }


    public function destroyTier(PricingTier $tier)
    {
        $tier->delete();
        return response()->json(['success' => true]);
    }

    public function getSettings()
    {
        return response()->json(PricingSetting::current());
    }
}
