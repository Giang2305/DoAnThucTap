<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChaptersController extends Controller
{
    public function store(Request $request){
        $chapter = Chapter::create([
            'title' => $request->title,
            'course_id' => $request->course_id,
        ]);

        return response()->json([
            'success' => true,
            'chapter' => $chapter,
            'course' => $chapter->course
        ]);
    }
}
