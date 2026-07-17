<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_settings', function (Blueprint $table) {
            // الحد الأقصى لعرض النص المسموح (سم)
            $table->decimal('max_text_width_cm', 6, 2)->default(25.00)->after('max_height_cm');
        });
    }

    public function down(): void
    {
        Schema::table('pricing_settings', function (Blueprint $table) {
            $table->dropColumn(['max_text_width_cm']);
        });
    }
};
