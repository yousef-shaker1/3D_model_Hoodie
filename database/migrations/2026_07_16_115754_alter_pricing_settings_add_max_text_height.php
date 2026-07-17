<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pricing_settings', function (Blueprint $table) {
            $table->decimal('max_text_height_cm', 6, 2)->default(10.00)->after('max_text_width_cm');
            if (Schema::hasColumn('pricing_settings', 'image_print_area_width_cm')) {
                $table->dropColumn(['image_print_area_width_cm', 'image_print_area_height_cm']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricing_settings', function (Blueprint $table) {
            $table->dropColumn('max_text_height_cm');
            $table->decimal('image_print_area_width_cm', 6, 2)->default(18.50)->after('print_area_height_cm');
            $table->decimal('image_print_area_height_cm', 6, 2)->default(24.30)->after('image_print_area_width_cm');
        });
    }
};
