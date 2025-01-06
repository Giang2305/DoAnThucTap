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
        Schema::create('tbl_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Liên kết đến tbl_user
            $table->unsignedBigInteger('course_id')->nullable(); // Liên kết đến tbl_courses (nếu có)
            $table->decimal('amount', 15, 2); // Số tiền thanh toán
            $table->enum('method', ['Momo', 'Bank Transfer']); // Phương thức thanh toán
            $table->string('transaction_id')->nullable(); // Mã giao dịch
            $table->enum('status', ['Pending', 'Completed', 'Failed'])->default('Pending'); // Trạng thái thanh toán
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
        Schema::dropIfExists('tbl_payments');
    }
};

