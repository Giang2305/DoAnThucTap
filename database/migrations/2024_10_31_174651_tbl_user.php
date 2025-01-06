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
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique(); // Tên đăng nhập
            $table->string('password'); // Mật khẩu
            $table->enum('role', ['Teacher', 'Student', 'Admin']); // Vai trò
            $table->unsignedBigInteger('linked_id')->nullable(); // ID liên kết đến teacher hoặc student
            $table->string('linked_type')->nullable(); // Loại đối tượng liên kết (tbl_teacher hoặc tbl_students)
            $table->decimal('balance', 15, 2)->default(0);
            $table->enum('status', ['Active', 'Inactive'])->nullable();
            $table->timestamps();
            $table->index(['linked_id', 'linked_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user');
    }
};
