<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger', function (Blueprint $table) {
            // $table->increments('id');
            // $table->integer('so_id')->unsigned();
            // $table->foreign('so_id')->references('id')->on('so')->onDelete('cascade');
            // $table->integer('created_by')->unsigned();
            // $table->foreign('created_by')->references('id')->on('user')->onDelete('cascade');
            // $table->string('credit')->nullable();
            // $table->string('debit')->nullable();

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
        Schema::dropIfExists('ledger');
    }
}
