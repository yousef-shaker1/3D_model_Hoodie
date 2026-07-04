<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\LogoSection;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){
         $sections = LogoSection::with('logos')->get();
         $colors = Color::active()->get();
    return view('welcome' ,compact('sections', 'colors'));
    }
}
