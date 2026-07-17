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
        Schema::create('pricing_settings', function (Blueprint $table) {
            $table->id();
            // سعر التيشيرت الأساسي (البيز)
            $table->unsignedInteger('tshirt_base_price')->default(100);
            // أبعاد منطقة الطباعة بالسنتيمتر
            $table->decimal('print_area_width_cm', 6, 2)->default(18.50);
            $table->decimal('print_area_height_cm', 6, 2)->default(24.30);
            // شرائح الأسعار: [{max_area_cm2: 100, price: 30}, ...]
            $table->json('pricing_tiers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_settings');
    }
};
