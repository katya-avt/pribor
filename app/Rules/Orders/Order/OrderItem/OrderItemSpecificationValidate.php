<?php

namespace App\Rules\Orders\Order\OrderItem;

use App\Models\Range\Item;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class OrderItemSpecificationValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected string $message = '';

    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $orderItem = Item::getItemByDrawing($value);

        if ($orderItem) {

            $orderItemSpecification =
                DB::select(DB::raw("WITH RECURSIVE manufacturing_data AS (
SELECT specification_cover_union.manufacturing_number, specification_cover_union.item_id,
specification_cover_union.cnt FROM
(
SELECT specification_item.specification_number AS manufacturing_number, specification_item.item_id, specification_item.cnt
FROM specification_item
UNION ALL
SELECT cover_item.cover_number, cover_item.item_id, cover_item.area * cover_item.consumption AS cnt FROM cover_item
) AS specification_cover_union
),
order_item_specification AS (
  SELECT
    i.id AS current_item_id,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL THEN manufacturing_data.item_id
      ELSE NULL
    END AS component_id
  FROM
    items AS i LEFT JOIN manufacturing_data ON (i.specification_number = manufacturing_data.manufacturing_number OR i.cover_number = manufacturing_data.manufacturing_number)
  WHERE
    i.id = $orderItem->id

  UNION ALL

  SELECT
    i.id AS current_item_id,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL THEN manufacturing_data.item_id
      ELSE NULL
    END AS component_id
  FROM
    order_item_specification ois,
    items AS i LEFT JOIN manufacturing_data ON (i.specification_number = manufacturing_data.manufacturing_number OR i.cover_number = manufacturing_data.manufacturing_number)
  WHERE i.id = ois.component_id
)
SELECT DISTINCT current_item_id FROM order_item_specification;"));

            foreach ($orderItemSpecification as $item) {
                $currentItem = Item::find($item->current_item_id);

                if ($currentItem->itemType->isProprietary()) {
                    if (!$currentItem->specification_number) {
                        $this->message = __('rules.proprietary_items_must_have_specification');
                        return false;
                    }
                }

                if ($currentItem->itemType->isTolling()) {
                    if (!$currentItem->route_number) {
                        $this->message = __('rules.tolling_items_must_have_route');
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
