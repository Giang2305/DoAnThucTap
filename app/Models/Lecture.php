<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Lecture extends Model
{
    protected $table = 'tbl_lectures';

    public function chapter() {
        return $this->belongsTo(Chapter::class);  // Bài giảng thuộc về 1 chương
    }
    public function course() {
        return $this->belongsTo(Course::class, 'course_id');  // Chương thuộc về 1 khóa học
    }
    public function exercises()
    {
        return $this->hasMany(Exercises::class, 'lecture_id', 'id');
    }
}
