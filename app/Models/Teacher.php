<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'tbl_teacher';
    protected $fillable = ['name', 'gender', 'date_of_birth', 'address', 'phone', 'email', 'education_level', 'exprience', 'department', 'start_date', 'status', 'profile_image'];
    public function user() 
    {
        return $this->morphOne(User::class, 'linked');
    }
}
