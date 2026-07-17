<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingTier extends Model
{
    protected $fillable = [
        'max_area_cm2',
        'price',
        'sort_order',
    ];

    protected $casts = [
        'max_area_cm2' => 'float',
        'price'        => 'integer',
        'sort_order'   => 'integer',
    ];

    /**
     * جيب كل المستويات مرتبة تصاعدياً حسب المساحة
     */
    public static function ordered(): \Illuminate\Database\Eloquent\Collection
    {
        return static::orderBy('max_area_cm2')->get();
    }

    /**
     * القيم الافتراضية عند إنشاء الجدول لأول مرة
     */
    public static function defaults(): array
    {
        return [
            ['max_area_cm2' => 50,   'price' => 30,  'sort_order' => 1],
            ['max_area_cm2' => 100,  'price' => 45,  'sort_order' => 2],
            ['max_area_cm2' => 200,  'price' => 60,  'sort_order' => 3],
            ['max_area_cm2' => 300,  'price' => 75,  'sort_order' => 4],
            ['max_area_cm2' => 500,  'price' => 90,  'sort_order' => 5],
            ['max_area_cm2' => 9999, 'price' => 120, 'sort_order' => 6],
        ];
    }

    /**
     * seed القيم الافتراضية إذا الجدول فارغ
     */
    public static function seedIfEmpty(): void
    {
        if (static::count() === 0) {
            foreach (static::defaults() as $tier) {
                static::create($tier);
            }
        }
    }
}
