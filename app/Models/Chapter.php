<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $table = 'tbl_chapters';
    protected $fillable = ['title','course_id'];

    public function course() {
        return $this->belongsTo(Course::class, 'course_id', 'id');  // Chương thuộc về 1 khóa học
    }

    public function lectures() {
        return $this->hasMany(Lecture::class);  // Chương có nhiều bài giảng
    }
}
