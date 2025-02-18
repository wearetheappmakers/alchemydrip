<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoOtherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_other', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('so_id')->unsigned();
            $table->foreign('so_id')->references('id')->on('so')->onDelete('cascade');
            $table->string('name');
            $table->string('price');
            $table->string('qty');
            $table->string('amount');
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
        Schema::dropIfExists('so_other');
    }
}
