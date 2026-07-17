<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingSetting extends Model
{
    protected $fillable = [
        'tshirt_base_price',
        'print_area_width_cm',
        'print_area_height_cm',
        'dtf_price_per_meter',
        'frame_width_cm',
        'profit_margin_percent',
        'min_print_price',
        'pricing_tiers',
        'max_width_cm',
        'max_height_cm',
        'max_text_width_cm',
        'max_text_height_cm',
        'max_image_width_cm',
        'max_image_height_cm',
    ];

    protected $casts = [
        'tshirt_base_price'          => 'integer',
        'print_area_width_cm'        => 'float',
        'print_area_height_cm'       => 'float',
        'dtf_price_per_meter'        => 'float',
        'frame_width_cm'             => 'float',
        'profit_margin_percent'      => 'integer',
        'min_print_price'            => 'integer',
        'pricing_tiers'              => 'array',
        'max_width_cm'               => 'float',
        'max_height_cm'              => 'float',
        'max_text_width_cm'          => 'float',
        'max_text_height_cm'         => 'float',
        'max_image_width_cm'         => 'float',
        'max_image_height_cm'        => 'float',
    ];

    /**
     * جيب الإعدادات الحالية أو القيم الافتراضية
     */
    public static function current(): array
    {
        $s = static::first();
        return [
            'tshirt_base_price'          => $s?->tshirt_base_price          ?? 100,
            'print_area_width_cm'        => $s?->print_area_width_cm        ?? 18.50,
            'print_area_height_cm'       => $s?->print_area_height_cm       ?? 24.30,
            'dtf_price_per_meter'        => $s?->dtf_price_per_meter        ?? 100.00,
            'frame_width_cm'             => $s?->frame_width_cm             ?? 59.00,
            'profit_margin_percent'      => $s?->profit_margin_percent      ?? 150,
            'min_print_price'            => $s?->min_print_price            ?? 30,
            'max_width_cm'               => $s?->max_width_cm               ?? 30.00, // Max for images
            'max_height_cm'              => $s?->max_height_cm              ?? 35.00, // Max for images
            'max_text_width_cm'          => $s?->max_text_width_cm          ?? 25.00, // Max for text
            'max_text_height_cm'         => $s?->max_text_height_cm         ?? 10.00, // Max for text
            'max_image_width_cm'         => $s?->max_image_width_cm         ?? 30.00, // Max for images
            'max_image_height_cm'        => $s?->max_image_height_cm        ?? 35.00, // Max for images
        ];
    }

    /**
     * احسب سعر الطباعة بناءً على أبعاد التصميم الفعلية بالسم
     *
     * المعادلة (بالمتر الطولي — الطريقة الأشيع في DTF):
     *   تكلفتك = (ارتفاع التصميم / 100) × سعر المتر الطولي
     *   سعر البيع = max(أقل سعر، تكلفة × (1 + هامش/100))
     *   التقريب: لأقرب 5 جنيه للأعلى
     *
     * ملاحظة: الفريم بيُقطع بناءً على الارتفاع فقط —
     * ما بتدفعش زيادة للعرض لأن الرول عرضه ثابت.
     */
    public static function calcPrintPrice(float $widthCm, float $heightCm): array
    {
        $s        = static::current();
        $margin   = $s['profit_margin_percent'];
        $minPrice = $s['min_print_price'];

        // التكلفة الفعلية: ارتفاع التصميم (متر) × سعر المتر
        $rawCost = ($heightCm / 100.0) * $s['dtf_price_per_meter'];

        // سعر البيع بعد هامش الربح
        $sellPrice = $rawCost * (1 + $margin / 100.0);

        // تطبيق الحد الأدنى والتقريب
        $sellPrice = ceil(max($minPrice, $sellPrice) / 5) * 5;

        return [
            'width_cm'        => round($widthCm, 1),
            'height_cm'       => round($heightCm, 1),
            'area_cm2'        => round($widthCm * $heightCm, 1),
            'cost_egp'        => round($rawCost, 2),
            'print_price_egp' => (int) $sellPrice,
        ];
    }
}
