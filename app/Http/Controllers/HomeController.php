<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    public function index(){
        $all_courses = DB::table('tbl_courses')
                    ->orderByDesc('id')
                    ->get();
        
        return view('home', compact('all_courses'));
    }
}
