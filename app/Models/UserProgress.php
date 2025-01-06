<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;
    protected $table = 'tbl_user_progress';

    // Định nghĩa các trường có thể điền được
    protected $fillable = [
        'users_id',
        'lectures_id',
        'exercises_id',
        'courses_id',
        'progress',
        'status',
        'completed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class, 'lectures_id');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class, 'exercises_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'courses_id');
    }
}
