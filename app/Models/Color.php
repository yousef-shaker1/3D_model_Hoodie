<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'hex_code', 'active', 'sort_order', 'sizes'];
    
    protected $casts = [
        'sizes' => 'array',
    ];
    
    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('sort_order');
    }
}
