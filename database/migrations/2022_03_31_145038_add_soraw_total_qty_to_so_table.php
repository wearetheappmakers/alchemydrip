<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSorawTotalQtyToSoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('so', function (Blueprint $table) {
            $table->integer('soraw_total_qty')->nullable()->after('other_total_amount');
            $table->integer('soraw_total_amount')->nullable()->after('soraw_total_qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('so', function (Blueprint $table) {
            //
        });
    }
}
