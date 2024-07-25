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
        Schema::create('order_item_route', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('current_item_id');
            $table->string('route_number', 64);
            $table->unsignedTinyInteger('point_number');
            $table->unsignedDecimal('cnt', 10, 2);
            $table->string('point_code', 32)->nullable(false);
            $table->string('operation_code', 4)->nullable(false);
            $table->string('rate_code', 16)->nullable(false);
            $table->unsignedDecimal('unit_time', 6, 2);
            $table->unsignedDecimal('working_time', 6, 2);
            $table->unsignedDecimal('lead_time', 6, 2);
            $table->unsignedDecimal('base_payment', 4, 2);

            $table->primary(['order_id', 'item_id', 'current_item_id', 'route_number', 'point_number']);
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('current_item_id')->references('id')->on('items');
            $table->foreign('route_number')->references('number')->on('routes');
            $table->foreign('point_code')->references('code')->on('points');
            $table->foreign('operation_code')->references('code')->on('operations');
            $table->foreign('rate_code')->references('code')->on('rates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item_route');
    }
};
