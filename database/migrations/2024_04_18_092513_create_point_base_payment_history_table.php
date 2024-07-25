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
        Schema::create('point_base_payment_history', function (Blueprint $table) {
            $table->string('point_code', 32);
            $table->timestamp('change_time')->useCurrent();
            $table->unsignedDecimal('new_value', 4, 2);

            $table->primary(['point_code', 'change_time']);
            $table->foreign('point_code')->references('code')->on('points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_base_payment_history');
    }
};
