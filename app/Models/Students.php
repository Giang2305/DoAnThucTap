<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;

    protected $table = 'tbl_students';
    protected $fillable = ['name', 'gender', 'date_of_birth', 'address', 'phone', 'email', 'profile_image', 'start_date', 'status'];
    public function user()
    {
        return $this->hasOne(User::class, 'linked_id', 'id');
    }
}
