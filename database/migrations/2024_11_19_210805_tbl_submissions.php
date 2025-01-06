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
        Schema::create('tbl_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id'); // ID học viên
            $table->unsignedBigInteger('exercises_id'); // ID bài tập
            $table->string('file_path'); // Đường dẫn tới file đã nộp
            $table->integer('score')->nullable(); // Điểm số (chỉ định khi giáo viên chấm điểm)
            $table->enum('status', ['Incomplete', 'Completed'])->default('Incomplete');
            $table->timestamps();   
            $table->foreign('users_id')->references('id')->on('tbl_user')->onDelete('cascade');
            $table->foreign('exercises_id')->references('id')->on('tbl_exercises')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_submissions');
    }
};
