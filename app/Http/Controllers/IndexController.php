<?php

namespace App\Http\Controllers;

use App\Models\LogoSection;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){
         $sections = LogoSection::with('logos')->get();
    return view('welcome' ,compact('sections'));
    }
}
