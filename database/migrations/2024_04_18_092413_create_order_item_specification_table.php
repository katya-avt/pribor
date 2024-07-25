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
        Schema::create('order_item_specification', function (Blueprint $table) {
            $table->string('order_item_specification_id', 128)->default(0);
            $table->unsignedBigInteger('order_id')->nullable(false);
            $table->unsignedBigInteger('item_id')->nullable(false);
            $table->unsignedBigInteger('current_item_id')->nullable(false);
            $table->unsignedBigInteger('current_item_parent_id')->nullable()->default(null);
            $table->string('current_specification_number', 64)->nullable();
            $table->string('current_cover_number', 64)->nullable();
            $table->string('current_number', 64)->nullable(false);
            $table->unsignedBigInteger('component_id')->nullable(false);
            $table->string('order_item_specification_parent_id', 128)->nullable()->default(null);
            $table->unsignedDecimal('component_cnt', 8, 2)->nullable(false);
            $table->unsignedDecimal('component_purchase_price', 10, 2)->nullable()->default(null);
            $table->unsignedDecimal('total_component_purchase_price', 18, 2)->nullable(false);

            $table->primary('order_item_specification_id');
            $table->unique(['order_id', 'item_id', 'current_item_id', 'current_item_parent_id', 'component_id'], 'order_item_specification_unique');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('current_item_id')->references('id')->on('items');
            $table->foreign('current_item_parent_id')->references('id')->on('items');
            $table->foreign('current_specification_number')->references('number')->on('specifications');
            $table->foreign('current_cover_number')->references('number')->on('covers');
            $table->foreign('component_id')->references('id')->on('items');
            $table->foreign('order_item_specification_parent_id', 'order_item_specification_parent_id_foreign')
                ->references('order_item_specification_id')->on('order_item_specification')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item_specification');
    }
};
