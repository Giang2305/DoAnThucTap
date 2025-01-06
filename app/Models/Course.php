<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Course extends Model
{
   protected $table = 'tbl_courses';
   protected $fillable = ['Name', 'Description', 'Teacher', 'Image', 'Price', 'is_active', 'created_at  ', 'updated_at  ', 'status'];
   public function chapters() {
        return $this->hasMany(Chapter::class, 'course_id', 'id');  // Khóa học có nhiều chương
    }

    // Lấy khóa học với các chương và bài giảng (phương thức tùy chỉnh)
    public function getWithLectures()
    {
        return $this->with('chapters.lectures')->get();
    }
    public function userProgress()
    {
        return $this->hasMany(UserProgress::class, 'courses_id', 'id');
    }

}
