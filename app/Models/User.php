<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'tbl_user';

    protected $fillable = ['name', 'username', 'password', 'role', 'status', 'balance', 'linked_id'];

    public function linked()
    {
        return $this->morphTo(null, 'linked_type', 'linked_id');
    }
    public function teacher(): HasOne {
        return $this->hasOne(Teacher::class, 'id', 'linked_id');
    }

    public function student(): HasOne {
        return $this->hasOne(Students::class, 'id', 'linked_id');
    }
    public function registeredCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
                    ->withPivot('progress')
                    ->withTimestamps();
    }
     /**
    use HasApiTokens, HasFactory, Notifiable;

   
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];*/
}
