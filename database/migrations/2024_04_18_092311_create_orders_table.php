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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 255)->nullable(false)->unique();
            $table->string('name', 255)->nullable(false)->unique();
            $table->date('creation_date')->nullable(false);
            $table->date('launch_date')->nullable()->default(null);
            $table->date('closing_date')->nullable(false);
            $table->date('completion_date')->nullable()->default(null);
            $table->text('note')->nullable()->default(null);
            $table->unsignedTinyInteger('status_id')->nullable(false);
            $table->char('customer_inn', 10)->nullable(false);
            $table->unsignedDecimal('amount', 18, 2)->nullable(false)->default(0);
            $table->unsignedDecimal('cost', 18, 2)->nullable(false)->default(0);

            $table->foreign('status_id')->references('id')->on('order_statuses');
            $table->foreign('customer_inn')->references('inn')->on('customers');

            $table->index('creation_date');
            $table->index('launch_date');
            $table->index('closing_date');
            $table->index('completion_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
