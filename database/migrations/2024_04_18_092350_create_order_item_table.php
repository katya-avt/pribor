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
        Schema::create('order_item', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedDecimal('per_unit_price', 10, 2);
            $table->unsignedDecimal('cnt', 8, 2);
            $table->unsignedDecimal('amount', 18, 2)->nullable(false)->default(0);
            $table->unsignedDecimal('cost', 18, 2)->nullable(false)->default(0);

            $table->primary(['order_id', 'item_id']);
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item');
    }
};
