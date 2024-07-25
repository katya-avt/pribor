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
        Schema::create('route_point', function (Blueprint $table) {
            $table->string('route_number', 64);
            $table->unsignedTinyInteger('point_number');
            $table->string('point_code', 32);
            $table->string('operation_code', 4);
            $table->string('rate_code', 16);
            $table->unsignedDecimal('unit_time', 6, 2);
            $table->unsignedDecimal('working_time', 6, 2);
            $table->unsignedDecimal('lead_time', 6, 2);

            $table->primary(['route_number', 'point_number']);
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
        Schema::dropIfExists('route_point');
    }
};
