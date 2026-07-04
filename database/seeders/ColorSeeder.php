<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'أسود', 'hex_code' => '#1a1a1a', 'active' => true, 'sort_order' => 1],
            ['name' => 'أبيض', 'hex_code' => '#ffffff', 'active' => true, 'sort_order' => 2],
            ['name' => 'أزرق داكن', 'hex_code' => '#2c3e50', 'active' => true, 'sort_order' => 3],
            ['name' => 'بنفسجي', 'hex_code' => '#8e44ad', 'active' => true, 'sort_order' => 4],
            ['name' => 'أحمر', 'hex_code' => '#e74c3c', 'active' => true, 'sort_order' => 5],
            ['name' => 'أزرق', 'hex_code' => '#3498db', 'active' => true, 'sort_order' => 6],
            ['name' => 'أخضر', 'hex_code' => '#27ae60', 'active' => true, 'sort_order' => 7],
            ['name' => 'برتقالي', 'hex_code' => '#f39c12', 'active' => true, 'sort_order' => 8],
            ['name' => 'وردي', 'hex_code' => '#e91e63', 'active' => true, 'sort_order' => 9],
            ['name' => 'رمادي', 'hex_code' => '#607d8b', 'active' => true, 'sort_order' => 10],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}
