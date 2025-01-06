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
        Schema::create('tbl_chapters', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('course_id')  // Khóa ngoại tới bảng courses
                  ->constrained('tbl_courses')  // Liên kết với bảng courses
                  ->onDelete('cascade');    // Xóa khóa học thì xóa luôn chương
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
        Schema::dropIfExists('tbl_chapters');
    }
};
