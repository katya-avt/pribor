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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('drawing', 255)->nullable(false)->unique();
            $table->string('name', 255)->nullable(false)->unique();
            $table->unsignedDecimal('cnt', 8, 2)->nullable(false)->default(0);

            $table->unsignedTinyInteger('item_type_id')->nullable(false);
            $table->unsignedTinyInteger('group_id')->nullable(false);
            $table->string('unit_code', 4)->nullable(false);
            $table->string('main_warehouse_code', 32)->nullable(false);
            $table->unsignedTinyInteger('manufacture_type_id')->nullable(false);
            $table->string('specification_number', 64)->nullable()->default(null);
            $table->string('cover_number', 64)->nullable()->default(null);
            $table->string('route_number', 64)->nullable()->default(null);
            $table->timestamp('added_to_order_at')->nullable()->default(null);
            $table->softDeletes();

            $table->foreign('item_type_id')->references('id')->on('item_types');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('unit_code')->references('code')->on('units');
            $table->foreign('main_warehouse_code')->references('code')->on('main_warehouses');
            $table->foreign('manufacture_type_id')->references('id')->on('manufacture_types');
            $table->foreign('specification_number')->references('number')->on('specifications');
            $table->foreign('cover_number')->references('number')->on('covers');
            $table->foreign('route_number')->references('number')->on('routes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
};
