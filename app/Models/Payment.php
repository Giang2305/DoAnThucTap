<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'tbl_payments';

    // Các cột có thể được gán hàng loạt
    protected $fillable = [
        'user_id',
        'course_id',
        'amount',
        'method',
        'transaction_id',
        'status',
    ];

    // Các kiểu dữ liệu cần cast
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ với Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
