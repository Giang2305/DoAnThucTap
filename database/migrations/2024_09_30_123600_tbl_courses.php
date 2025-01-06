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
        Schema::create('tbl_courses', function (Blueprint $table) {
            $table->id();
            $table->string('Name')->nullable();
            $table->string('Description')->nullable();
            $table->string('Teacher')->nullable();
            $table->string('Image')->nullable();
            $table->string('Price')->nullable();
            $table->string('progress')->nullable()->default(0);
            $table->string('is_active')->nullable();
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
        Schema::dropIfExists('tbl_courses');
    }
};
