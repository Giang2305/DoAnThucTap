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
        Schema::create('tbl_exercises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lecture_id'); // Khóa ngoại liên kết với bài giảng
            $table->string('title'); // Tiêu đề bài tập
            $table->text('description')->nullable(); // Mô tả bài tập, có thể để trống
            $table->string('file_path')->nullable(); // Đường dẫn file tài liệu, có thể để trống
            $table->date('due_date')->nullable(); // Ngày hết hạn nộp bài, có thể để trống
            $table->enum('status', ['pending', 'completed'])->default('pending'); // Trạng thái bài tập
            $table->timestamps();

            $table->foreign('lecture_id')->references('id')->on('tbl_lectures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_exercises');
    }
};
