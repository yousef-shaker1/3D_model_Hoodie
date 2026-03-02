<?php

namespace App\Models;

use App\Models\logo as LogoModel;
use Illuminate\Database\Eloquent\Model;

class LogoSection extends Model
{
    protected $table = 'logo_sections';
    protected $fillable = ['name', 'logo'];

    public function logos()
{
    return $this->hasMany(LogoModel::class, 'logo_section_id');
}
}
