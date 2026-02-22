<?php

namespace App\Models;

use App\Models\LogoSection;
use Illuminate\Database\Eloquent\Model;

class logo extends Model
{
    protected $fillable = ['logo_section_id', 'image'];
    public function section()
    {
        return $this->belongsTo(LogoSection::class, 'logo_section_id');
    }
}
