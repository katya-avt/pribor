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
        Schema::create('cover_item', function (Blueprint $table) {
            $table->string('cover_number', 64);
            $table->unsignedBigInteger('item_id');
            $table->unsignedDecimal('area', 7, 2);
            $table->unsignedDecimal('consumption', 6, 5);

            $table->primary(['cover_number', 'item_id']);
            $table->foreign('cover_number')->references('number')->on('covers');
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
        Schema::dropIfExists('cover_item');
    }
};
