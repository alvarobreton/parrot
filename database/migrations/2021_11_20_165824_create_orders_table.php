<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();           
            $table->float('total_price');
            $table->integer('quantity');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('number_order')->nullable();

            $table->foreign('product_id')
                        ->references('id')->on('products')
                        ->onDelete('set null');

            $table->foreign('number_order')
                        ->references('id')->on('sales')
                        ->onDelete('set null');
                                ;
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
        Schema::dropIfExists('orders');
    }
}
