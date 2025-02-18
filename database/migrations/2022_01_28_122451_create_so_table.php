<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('number');
            $table->string('address')->nullable();
            $table->string('total_qty');
            $table->string('total_amount');
            $table->string('other_total_qty');
            $table->string('other_total_amount');
            $table->string('grand_total_qty')->nullable();
            $table->string('grand_total_amount')->nullable();
            $table->string('created_by')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('so');
    }
}
