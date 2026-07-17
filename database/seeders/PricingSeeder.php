<?php

namespace Database\Seeders;

use App\Models\PricingSetting;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    public function run(): void
    {
        PricingSetting::truncate();

        PricingSetting::create([
            'tshirt_base_price'     => 100,    // سعر التيشيرت الأساسي
            'print_area_width_cm'   => 18.50,  // عرض منطقة الطباعة على التيشيرت
            'print_area_height_cm'  => 24.30,  // ارتفاع منطقة الطباعة
            'dtf_price_per_meter'   => 100.00, // سعر المتر الطولي DTF من المورد
            'frame_width_cm'        => 59.00,  // عرض الفريم (للمعلومة فقط)
            'profit_margin_percent' => 150,    // هامش الربح 150% فوق تكلفة الطباعة
            'min_print_price'       => 30,     // أقل سعر طباعة
        ]);
    }
}
