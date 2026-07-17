<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_settings', function (Blueprint $table) {
            // الحد الأقصى للعرض المسموح للتصميم (سم)
            $table->decimal('max_width_cm', 6, 2)->default(30.00)->after('min_print_price');
            // الحد الأقصى للطول المسموح للتصميم (سم)
            $table->decimal('max_height_cm', 6, 2)->default(35.00)->after('max_width_cm');
        });
    }

    public function down(): void
    {
        Schema::table('pricing_settings', function (Blueprint $table) {
            $table->dropColumn(['max_width_cm', 'max_height_cm']);
        });
    }
};
