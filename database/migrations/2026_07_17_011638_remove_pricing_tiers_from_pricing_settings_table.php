<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_settings', function (Blueprint $table) {
            if (Schema::hasColumn('pricing_settings', 'pricing_tiers')) {
                $table->dropColumn('pricing_tiers');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pricing_settings', function (Blueprint $table) {
            $table->json('pricing_tiers')->nullable()->after('min_print_price');
        });
    }
};
