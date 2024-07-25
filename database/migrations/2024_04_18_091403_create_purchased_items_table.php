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
        Schema::create('purchased_items', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->primary();
            $table->unsignedDecimal('purchase_price', 10, 2);
            $table->unsignedDecimal('purchase_lot', 10, 2);
            $table->unsignedDecimal('order_point', 10, 2);
            $table->unsignedDecimal('unit_factor', 10, 6);
            $table->string('unit_code', 4);

            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('unit_code')->references('code')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchased_items');
    }
};
