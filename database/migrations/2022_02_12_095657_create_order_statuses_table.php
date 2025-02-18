<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
           $table->increments('id');
            $table->string('name');
            $table->text('label')->nullable();
            $table->string('description')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
        DB::table('order_statuses')->insert(
            array(
                'name' => 'Pending',
                'label' => "pending",
                'description' => "Your order is Pending",
            )
        );
        
        DB::table('order_statuses')->insert(
            array(
                'name' => 'Completed',
                'label' => "completed",
                'description' => "Your Order is Completed",
            )
        );
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_statuses');
    }
}
