<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE TRIGGER `update_base_payment` AFTER UPDATE ON `points`
 FOR EACH ROW BEGIN
 IF NEW.base_payment <> OLD.base_payment THEN
 INSERT INTO point_base_payment_history (point_code, new_value)
 VALUES (NEW.code, NEW.base_payment);
 END IF;
END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS `update_base_payment`");
    }
};
