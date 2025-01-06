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
        Schema::create('tbl_lectures', function (Blueprint $table) {
            $table->id();
            $table->integer('lecture_number');
            $table->string('title');
            $table->enum('type', ['Video', 'Text', 'File']);  // Kiểu bài giảng
            $table->string('content_url')->nullable();  // Link đến video/file nếu cần
            $table->text('text_content')->nullable();   // Nội dung text nếu là bài viết
            $table->string('video_url')->nullable();    
            $table->foreignId('chapter_id')  // Liên kết với bảng chapters
                  ->constrained('tbl_chapters')
                  ->onDelete('cascade');    // Xóa chương thì xóa luôn bài giảng
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
        Schema::dropIfExists('tbl_lectures');
    }
};
