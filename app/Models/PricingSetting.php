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
        'max_width_cm',
        'max_height_cm',
        'max_text_width_cm',
        'max_text_height_cm',
        'max_image_width_cm',
        'max_image_height_cm',
    ];

    protected $casts = [
        'tshirt_base_price'     => 'integer',
        'print_area_width_cm'   => 'float',
        'print_area_height_cm'  => 'float',
        'dtf_price_per_meter'   => 'float',
        'frame_width_cm'        => 'float',
        'profit_margin_percent' => 'integer',
        'min_print_price'       => 'integer',
        'max_width_cm'          => 'float',
        'max_height_cm'         => 'float',
        'max_text_width_cm'     => 'float',
        'max_text_height_cm'    => 'float',
        'max_image_width_cm'    => 'float',
        'max_image_height_cm'   => 'float',
    ];

    /**
     * جيب الإعدادات الحالية أو القيم الافتراضية
     */
    public static function current(): array
    {
        try {
            $s = static::first();

            // جيب مستويات التسعير من جدولها — seed القيم الافتراضية إذا فارغ
            PricingTier::seedIfEmpty();
            $tiers = PricingTier::ordered()
                ->map(fn($t) => ['max_area_cm2' => $t->max_area_cm2, 'price' => $t->price])
                ->values()
                ->toArray();

            return [
                'tshirt_base_price'     => $s?->tshirt_base_price     ?? 100,
                'print_area_width_cm'   => $s?->print_area_width_cm   ?? 18.50,
                'print_area_height_cm'  => $s?->print_area_height_cm  ?? 24.30,
                'dtf_price_per_meter'   => $s?->dtf_price_per_meter   ?? 100.00,
                'frame_width_cm'        => $s?->frame_width_cm        ?? 59.00,
                'profit_margin_percent' => $s?->profit_margin_percent ?? 150,
                'min_print_price'       => $s?->min_print_price       ?? 30,
                'max_width_cm'          => $s?->max_width_cm          ?? 30.00,
                'max_height_cm'         => $s?->max_height_cm         ?? 35.00,
                'max_text_width_cm'     => $s?->max_text_width_cm     ?? 25.00,
                'max_text_height_cm'    => $s?->max_text_height_cm    ?? 10.00,
                'max_image_width_cm'    => $s?->max_image_width_cm    ?? 30.00,
                'max_image_height_cm'   => $s?->max_image_height_cm   ?? 35.00,
                'pricing_tiers'         => $tiers,
            ];
        } catch (\Exception $e) {
            return [
                'tshirt_base_price'     => 100,
                'print_area_width_cm'   => 18.50,
                'print_area_height_cm'  => 24.30,
                'dtf_price_per_meter'   => 100.00,
                'frame_width_cm'        => 59.00,
                'profit_margin_percent' => 150,
                'min_print_price'       => 30,
                'max_width_cm'          => 30.00,
                'max_height_cm'         => 35.00,
                'max_text_width_cm'     => 25.00,
                'max_text_height_cm'    => 10.00,
                'max_image_width_cm'    => 30.00,
                'max_image_height_cm'   => 35.00,
                'pricing_tiers'         => [],
            ];
        }
    }

    /**
     * احسب سعر الطباعة بناءً على المساحة باستخدام pricing tiers من قاعدة البيانات
     */
    public static function calcPrintPrice(float $widthCm, float $heightCm): array
    {
        $s    = static::current();
        $area = $widthCm * $heightCm;
        $tiers = $s['pricing_tiers'] ?? [];

        // البحث عن tier المناسب
        $price = $s['min_print_price'] ?? 30;
        foreach ($tiers as $tier) {
            if ($area <= $tier['max_area_cm2']) {
                $price = $tier['price'];
                break;
            }
        }

        return [
            'width_cm'        => round($widthCm, 1),
            'height_cm'       => round($heightCm, 1),
            'area_cm2'        => round($area, 1),
            'cost_egp'        => $price,
            'print_price_egp' => (int) $price,
        ];
    }
}
