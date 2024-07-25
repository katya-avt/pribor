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
        Schema::create('purchased_item_purchase_price_history', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->timestamp('change_time')->useCurrent();
            $table->unsignedDecimal('new_value', 10, 2);

            $table->primary(['item_id', 'change_time']);
            $table->foreign('item_id')->references('item_id')->on('purchased_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchased_item_purchase_price_history');
    }
};
