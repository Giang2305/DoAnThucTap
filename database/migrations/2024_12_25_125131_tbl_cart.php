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
        Schema::create('tbl_cart', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID của người dùng
            $table->unsignedBigInteger('course_id'); // ID của khóa học
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('tbl_user')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('tbl_courses')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_cart');
    }
};
