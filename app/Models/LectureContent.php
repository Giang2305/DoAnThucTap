<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LectureContent extends Model
{
    protected $table = 'tbl_lecture_content';
    public function lectures() {
        return $this->belongsTo(Lecture::class);  // nội dung bài giảng
    }
}
