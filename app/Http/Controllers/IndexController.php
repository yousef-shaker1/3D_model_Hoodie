<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\LogoSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    public function index()
    {
        $sections = Cache::remember('logo_sections_with_logos', now()->addHours(6), function () {
            return LogoSection::with('logos')->get();
        });

        $colors = Cache::remember('active_colors', now()->addHours(6), function () {
            return Color::active()->get();
        });

        return view('welcome', compact('sections', 'colors'));
    }
}
