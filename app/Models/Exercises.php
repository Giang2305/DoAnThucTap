<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercises extends Model
{
    use HasFactory;

    protected $table ='tbl_exercises';
    protected $fillable = ['lecture_id','title','description','file_path','due_date','status'];
    public function lecture()
    {
        return $this->belongsTo(Lecture::class, 'lecture_id');
    }
}
