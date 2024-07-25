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
        DB::unprepared("CREATE TRIGGER `update_purchase_price` AFTER UPDATE ON `purchased_items`
 FOR EACH ROW BEGIN
 IF NEW.purchase_price <> OLD.purchase_price THEN
 INSERT INTO purchased_item_purchase_price_history (item_id, new_value)
 VALUES (NEW.item_id, NEW.purchase_price);
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
        DB::unprepared("DROP TRIGGER IF EXISTS `update_purchase_price`");
    }
};
