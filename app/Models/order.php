<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
        protected $fillable = [
        'name',
        'phone',
        'address',
        'size',
        'notes',
        'logos',
        'status',
    ];

    protected $casts = [
        'logos' => 'array',  // تحويل JSON تلقائياً لـ array
    ];

    // ألوان الـ status للعرض
    public function statusColor(): string
    {
        return match($this->status) {
            'pending'    => 'orange',
            'processing' => 'blue',
            'done'       => 'green',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pending'    => 'قيد الانتظار',
            'processing' => 'جاري التنفيذ',
            'done'       => 'تم التسليم',
            'cancelled'  => 'ملغي',
            default      => 'غير معروف',
        };
    }
}
