<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorates = [
            ['name' => 'القاهرة', 'shipping_price' => 50],
            ['name' => 'الجيزة', 'shipping_price' => 50],
            ['name' => 'الإسكندرية', 'shipping_price' => 60],
            ['name' => 'الدقهلية', 'shipping_price' => 70],
            ['name' => 'البحر الأحمر', 'shipping_price' => 100],
            ['name' => 'البحيرة', 'shipping_price' => 70],
            ['name' => 'الفيوم', 'shipping_price' => 80],
            ['name' => 'الغربية', 'shipping_price' => 70],
            ['name' => 'الإسماعيلية', 'shipping_price' => 70],
            ['name' => 'المنوفية', 'shipping_price' => 70],
            ['name' => 'المنيا', 'shipping_price' => 80],
            ['name' => 'القليوبية', 'shipping_price' => 60],
            ['name' => 'الوادي الجديد', 'shipping_price' => 120],
            ['name' => 'السويس', 'shipping_price' => 70],
            ['name' => 'اسوان', 'shipping_price' => 100],
            ['name' => 'اسيوط', 'shipping_price' => 90],
            ['name' => 'بني سويف', 'shipping_price' => 80],
            ['name' => 'بورسعيد', 'shipping_price' => 70],
            ['name' => 'دمياط', 'shipping_price' => 70],
            ['name' => 'الشرقية', 'shipping_price' => 70],
            ['name' => 'جنوب سيناء', 'shipping_price' => 120],
            ['name' => 'كفر الشيخ', 'shipping_price' => 70],
            ['name' => 'مطروح', 'shipping_price' => 120],
            ['name' => 'الأقصر', 'shipping_price' => 100],
            ['name' => 'قنا', 'shipping_price' => 100],
            ['name' => 'شمال سيناء', 'shipping_price' => 120],
            ['name' => 'سوهاج', 'shipping_price' => 90]
        ];

        foreach ($governorates as $gov) {
            \App\Models\Governorate::create($gov);
        }
    }
}
