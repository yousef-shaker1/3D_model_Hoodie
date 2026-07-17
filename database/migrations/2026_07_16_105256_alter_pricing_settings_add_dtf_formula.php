<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_settings', function (Blueprint $table) {
            // سعر متر DTF من المورد (ج.م / متر طولي)
            $table->decimal('dtf_price_per_meter', 8, 2)->default(100.00)->after('tshirt_base_price');
            // عرض الفريم / الرول (سم) — عادةً 59 أو 60 سم
            $table->decimal('frame_width_cm', 6, 2)->default(59.00)->after('dtf_price_per_meter');
            // هامش الربح % فوق التكلفة الفعلية
            $table->unsignedInteger('profit_margin_percent')->default(80)->after('frame_width_cm');
            // أقل سعر طباعة (ج.م) — حتى لو التصميم صغير جداً
            $table->unsignedInteger('min_print_price')->default(25)->after('profit_margin_percent');
        });
    }

    public function down(): void
    {
        Schema::table('pricing_settings', function (Blueprint $table) {
            $table->dropColumn(['dtf_price_per_meter', 'frame_width_cm', 'profit_margin_percent', 'min_print_price']);
        });
    }
};
