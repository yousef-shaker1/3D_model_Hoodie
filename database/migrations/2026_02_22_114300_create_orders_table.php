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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20);
            $table->string('address');
            $table->string('size', 5);   // S, M, L, XL, XXL
            $table->text('notes')->nullable();
            // بيانات اللوجوهات كـ JSON
            // كل عنصر: { src, view, x_percent, y_percent, width_percent, height_percent, rotation }
            $table->json('logos');
            $table->enum('status', ['pending', 'processing', 'done', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
