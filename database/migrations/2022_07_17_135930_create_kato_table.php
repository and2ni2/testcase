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
        Schema::create('kato', function (Blueprint $table) {
            $table->id();
            $table->string('te', 9);
            $table->string('ab', 2);
            $table->string('cd', 2);
            $table->string('ef', 2);
            $table->string('hij', 3);
            $table->integer('k');
            $table->string('parent', 9)->nullable();
            $table->integer('level');
            $table->string('name_ru');
            $table->string('name_kz');
            $table->string('fullname_ru');
            $table->string('fullname_kz');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
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
        Schema::dropIfExists('kato');
    }
};
