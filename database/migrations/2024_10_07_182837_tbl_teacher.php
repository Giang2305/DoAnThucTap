<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_teacher', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();//tên
            $table->enum('gender', ['male', 'female', 'other'])->nullable();//giới tính
            $table->date('date_of_birth')->nullable();//ngày sinh
            $table->string('address')->nullable();//địa chỉ
            $table->string('phone', 15)->nullable();//sđt
            $table->string('email')->unique()->nullable();//email
            $table->string('education_level')->nullable();//trình độ học vấn
            $table->text('experience')->nullable();//kinh nghiệm
            $table->string('department')->nullable();//môn
            $table->date('start_date')->nullable();//ngày làm
            $table->enum('status', ['Active', 'Inactive'])->nullable();//tình trạng hoạt động
            $table->string('profile_image')->nullable();//ảnh đại diện
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_teacher');
    }
};
