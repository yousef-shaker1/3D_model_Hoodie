<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::latest()->paginate(10);
        return view('dashboard.promo-codes.index', compact('promoCodes'));
    }

    public function create()
    {
        return view('dashboard.promo-codes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_fixed' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
            'is_active' => 'nullable|boolean',
        ]);

        PromoCode::create([
            'code' => strtoupper($request->code),
            'discount_percent' => $request->discount_percent ?? 0,
            'discount_fixed' => $request->discount_fixed ?? 0,
            'max_uses' => $request->max_uses,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('promo-codes.index')->with('success', 'تم إضافة كود الخصم بنجاح');
    }

    public function show(PromoCode $promoCode)
    {
        return view('dashboard.promo-codes.show', compact('promoCode'));
    }

    public function edit(PromoCode $promoCode)
    {
        return view('dashboard.promo-codes.edit', compact('promoCode'));
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code,' . $promoCode->id,
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_fixed' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
            'is_active' => 'nullable|boolean',
        ]);

        $promoCode->update([
            'code' => strtoupper($request->code),
            'discount_percent' => $request->discount_percent ?? 0,
            'discount_fixed' => $request->discount_fixed ?? 0,
            'max_uses' => $request->max_uses,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('promo-codes.index')->with('success', 'تم تحديث كود الخصم بنجاح');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        return redirect()->route('promo-codes.index')->with('success', 'تم حذف كود الخصم بنجاح');
    }

    public function validateCode(Request $request)
    {
        $code = strtoupper($request->code);
        $promoCode = PromoCode::where('code', $code)->first();

        if (!$promoCode || !$promoCode->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'كود الخصم غير صالح أو منتهي الصلاحية'
            ]);
        }

        return response()->json([
            'valid' => true,
            'discount_percent' => $promoCode->discount_percent,
            'discount_fixed' => $promoCode->discount_fixed,
            'message' => 'كود الخصم صالح'
        ]);
    }
}
