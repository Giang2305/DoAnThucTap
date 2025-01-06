<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $table = 'tbl_submissions';

    // protected $fillable = [
    //     'users_id',
    //     'exercises_id',
    //     'file_path',
    //     'score',
    //     'status',
    // ];
    protected $fillable = [
        'users_id',
        'exercises_id',
        'file_path',
        'status',
    ];

    // Quan hệ với User
    public function user() {
        return $this->belongsTo(User::class, 'users_id');
    }

    // Quan hệ với Exercise
    public function exercise() {
        return $this->belongsTo(Exercise::class, 'exercises_id');
    }
}

