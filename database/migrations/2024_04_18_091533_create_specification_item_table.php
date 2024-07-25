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
        Schema::create('specification_item', function (Blueprint $table) {
            $table->string('specification_number', 64);
            $table->unsignedBigInteger('item_id');
            $table->unsignedDecimal('cnt', 7, 5);

            $table->primary(['specification_number', 'item_id']);
            $table->foreign('specification_number')->references('number')->on('specifications');
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
        Schema::dropIfExists('specification_item');
    }
};
