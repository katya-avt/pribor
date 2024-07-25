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
        DB::unprepared("CREATE TRIGGER `insert_primary_and_foreign_key` BEFORE INSERT ON `order_item_specification`
 FOR EACH ROW BEGIN
DECLARE parent_id VARCHAR(128);
SET NEW.order_item_specification_id = CONCAT_WS('-', NEW.order_id, NEW.item_id, NEW.current_item_id, NEW.current_item_parent_id, NEW.component_id);
SELECT order_item_specification_id
INTO parent_id
FROM order_item_specification
WHERE order_id = NEW.order_id AND item_id = NEW.item_id AND current_item_id = NEW.current_item_parent_id AND component_id = NEW.current_item_id;
SET NEW.order_item_specification_parent_id = parent_id;
END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS `insert_primary_and_foreign_key`");
    }
};
