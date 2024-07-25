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
        Schema::create('item_cover', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->string('cover_number', 64);

            $table->primary(['item_id', 'cover_number']);
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('cover_number')->references('number')->on('covers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_cover');
    }
};
