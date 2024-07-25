<?php

namespace App\Services\Range\Item;

use App\Models\Range\Department;
use App\Models\Range\Item;
use App\Models\Range\Route;
use App\Models\Range\Specification;
use Illuminate\Support\Facades\DB;

class ShowService
{
    public function getDetailAdditionalInformation(Item $item)
    {
        $detailAdditionalInformation = [
            'blackWeight' => '',
            'netWeight' => '',
            'kim' => ''
        ];

        if ($item->detail) {
            if ($item->specification_number) {
                $detailSpecification = Specification::find($item->specification_number);
                $blackWeight = $detailSpecification->items()->first()->pivot->cnt;

                $detailSize = self::getSize($item->detail->detail_size);
                $billetSize = self::getSize($item->detail->billet_size);

                $netWeight = $detailSize * ($blackWeight/$billetSize);
                $kim = $netWeight/$blackWeight;

                $detailAdditionalInformation['blackWeight'] = $blackWeight;
                $detailAdditionalInformation['netWeight'] = round($netWeight, 5);
                $detailAdditionalInformation['kim'] = round($kim, 3);
            }
        }
        return $detailAdditionalInformation;
    }

    public function getMaterialsCost(Item $item)
    {
        if ($item->specification_number) {
            $specificationItemsCost = self::getSpecificationItemsCost($item);
            $specificationItemsRouteCost = self::getSpecificationItemsRouteCost($item);

            return $specificationItemsCost + $specificationItemsRouteCost;
        }

        if ($item->itemType->isPurchased()) {
            return $item->purchasedItem->purchase_price;
        }

        return 0;
    }

    public function getCoverCost(Item $item)
    {
        if ($item->cover_number) {
            $coverItemsCost = self::getCoverItemsCost($item);
            $coverItemsRouteCost = self::getCoverItemsRouteCost($item);

            return $coverItemsCost + $coverItemsRouteCost;
        }

        return 0;
    }

    public function getSalary(Item $item)
    {
        if ($item->route_number) {

            return DB::select("WITH route_data AS (
SELECT route_point.route_number, route_point.point_number, route_point.point_code,
route_point.operation_code, route_point.rate_code, rates.factor,
route_point.unit_time, route_point.working_time, route_point.lead_time, points.base_payment
FROM route_point
LEFT JOIN points ON route_point.point_code = points.code
LEFT JOIN rates ON route_point.rate_code = rates.code
WHERE route_point.route_number = $item->route_number
)

SELECT SUM(route_data.factor *
(route_data.unit_time + route_data.working_time + route_data.lead_time) * route_data.base_payment)
AS total_salary
FROM route_data;")[0]->total_salary;
        }

        return 0;
    }

    public function getDepartmentsCorrelationForItem(Item $item)
    {
        $departments = Department::pluck('name');

        if ($item->route_number) {

            $totalPointCnt = Route::find($item->route_number)->points()->count();

            $itemRouteDepartmentsNameAndCnt = Route::find($item->route_number)->points()->with('department')
                ->get()->groupBy('department.name')->map(function ($items) use ($totalPointCnt) {
                    return $items->count() / $totalPointCnt;
                });

            $missingDepartments = $departments->diff($itemRouteDepartmentsNameAndCnt->keys());

            $missingDepartmentsNameAndCnt = $missingDepartments->flip()->map(function () {
                return 0;
            });

            return $itemRouteDepartmentsNameAndCnt->merge($missingDepartmentsNameAndCnt)->sortKeys();
        }

        return $departments->flip()->map(function () {
            return 0;
        });
    }

    private function getSize(string $size)
    {
        $separator = 'x';
        $measurements = explode($separator, $size);

        return array_product($measurements);
    }

    private function getSpecificationItemsCost(Item $item)
    {
        return DB::select("WITH RECURSIVE manufacturing_data AS (
SELECT specification_cover_union.manufacturing_number, specification_cover_union.item_id,
specification_cover_union.cnt, purchased_items.purchase_price FROM
(
SELECT specification_item.specification_number AS manufacturing_number, specification_item.item_id, specification_item.cnt
FROM specification_item
UNION ALL
SELECT cover_item.cover_number, cover_item.item_id, cover_item.area * cover_item.consumption AS cnt
FROM cover_item
) AS specification_cover_union
LEFT JOIN purchased_items ON specification_cover_union.item_id = purchased_items.item_id),
order_item_specification AS (
  SELECT
    i.id AS current_item_id,
    CASE
      WHEN i.specification_number IS NOT NULL THEN manufacturing_data.item_id
      ELSE NULL
    END AS component_id,
    CASE
      WHEN i.specification_number IS NOT NULL
      THEN manufacturing_data.cnt
      ELSE NULL
    END AS component_cnt,
    manufacturing_data.purchase_price AS component_purchase_price,
    CASE
      WHEN manufacturing_data.purchase_price IS NULL
      THEN manufacturing_data.cnt
      ELSE manufacturing_data.cnt * manufacturing_data.purchase_price
    END AS total_component_purchase_price
  FROM
    items AS i LEFT JOIN manufacturing_data ON i.specification_number = manufacturing_data.manufacturing_number
  WHERE
    i.id = $item->id

  UNION ALL

  SELECT
    i.id AS current_item_id,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL
      THEN manufacturing_data.item_id
      ELSE NULL
    END AS component_id,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL
      THEN manufacturing_data.cnt * ois.component_cnt
      ELSE NULL
    END AS component_cnt,
    manufacturing_data.purchase_price AS component_purchase_price,
    CASE
      WHEN manufacturing_data.purchase_price IS NULL
      THEN manufacturing_data.cnt * ois.component_cnt
      ELSE manufacturing_data.cnt * ois.component_cnt * manufacturing_data.purchase_price
    END AS total_component_purchase_price
  FROM
    order_item_specification ois,
    items AS i LEFT JOIN manufacturing_data
    ON (i.specification_number = manufacturing_data.manufacturing_number OR i.cover_number = manufacturing_data.manufacturing_number)
  WHERE i.id = ois.component_id
)
SELECT SUM(total_component_purchase_price) AS total_purchase_price FROM order_item_specification
       WHERE component_id IS NOT NULL AND component_purchase_price IS NOT NULL;")[0]->total_purchase_price;
    }

    private function getCoverItemsCost(Item $item)
    {
        return DB::select("WITH RECURSIVE manufacturing_data AS (
SELECT specification_cover_union.manufacturing_number, specification_cover_union.item_id,
specification_cover_union.cnt, purchased_items.purchase_price FROM
(
SELECT specification_item.specification_number AS manufacturing_number, specification_item.item_id, specification_item.cnt
FROM specification_item
UNION ALL
SELECT cover_item.cover_number, cover_item.item_id, cover_item.area * cover_item.consumption AS cnt
FROM cover_item
) AS specification_cover_union
LEFT JOIN purchased_items ON specification_cover_union.item_id = purchased_items.item_id),
order_item_specification AS (
  SELECT
    i.id AS current_item_id,
    CASE
      WHEN i.cover_number IS NOT NULL THEN manufacturing_data.item_id
      ELSE NULL
    END AS component_id,
    CASE
      WHEN i.cover_number IS NOT NULL
      THEN manufacturing_data.cnt
      ELSE NULL
    END AS component_cnt,
    manufacturing_data.purchase_price AS component_purchase_price,
    CASE
      WHEN manufacturing_data.purchase_price IS NULL
      THEN manufacturing_data.cnt
      ELSE manufacturing_data.cnt * manufacturing_data.purchase_price
    END AS total_component_purchase_price
  FROM
    items AS i LEFT JOIN manufacturing_data ON i.cover_number = manufacturing_data.manufacturing_number
  WHERE
    i.id = $item->id

  UNION ALL

  SELECT
    i.id AS current_item_id,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL
      THEN manufacturing_data.item_id
      ELSE NULL
    END AS component_id,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL
      THEN manufacturing_data.cnt * ois.component_cnt
      ELSE NULL
    END AS component_cnt,
    manufacturing_data.purchase_price AS component_purchase_price,
    CASE
      WHEN manufacturing_data.purchase_price IS NULL
      THEN manufacturing_data.cnt * ois.component_cnt
      ELSE manufacturing_data.cnt * ois.component_cnt * manufacturing_data.purchase_price
    END AS total_component_purchase_price
  FROM
    order_item_specification ois,
    items AS i LEFT JOIN manufacturing_data
    ON (i.specification_number = manufacturing_data.manufacturing_number OR i.cover_number = manufacturing_data.manufacturing_number)
  WHERE i.id = ois.component_id
)
SELECT SUM(total_component_purchase_price) AS total_purchase_price FROM order_item_specification
       WHERE component_id IS NOT NULL AND component_purchase_price IS NOT NULL;")[0]->total_purchase_price;
    }

    private function getSpecificationItemsRouteCost(Item $item)
    {
        return DB::select("WITH route_data AS (
SELECT items.id, route_point.route_number, route_point.point_number, route_point.point_code,
route_point.operation_code, route_point.rate_code, rates.factor,
route_point.unit_time, route_point.working_time, route_point.lead_time, points.base_payment
FROM items LEFT JOIN route_point ON items.route_number = route_point.route_number
LEFT JOIN points ON route_point.point_code = points.code
LEFT JOIN rates ON route_point.rate_code = rates.code
),

all_components AS (
WITH RECURSIVE manufacturing_data AS (
SELECT specification_cover_union.manufacturing_number, specification_cover_union.item_id,
specification_cover_union.cnt FROM
(
SELECT specification_item.specification_number AS manufacturing_number, specification_item.item_id, specification_item.cnt
FROM specification_item
UNION ALL
SELECT cover_item.cover_number, cover_item.item_id, cover_item.area * cover_item.consumption AS cnt FROM cover_item
) AS specification_cover_union
),
recursive_order_item_specification AS (
  SELECT
    i.id AS current_item_id,
    CASE
      WHEN i.specification_number IS NOT NULL THEN manufacturing_data.item_id
      ELSE NULL
    END AS component_id,
    CASE
      WHEN i.specification_number IS NOT NULL THEN manufacturing_data.cnt
      ELSE NULL
    END AS component_cnt
  FROM
    items AS i LEFT JOIN manufacturing_data ON i.specification_number = manufacturing_data.manufacturing_number
  WHERE
    i.id = $item->id

  UNION ALL

  SELECT
    i.id AS current_item_id,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL THEN manufacturing_data.item_id
      ELSE NULL
    END AS component_id,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL
      THEN manufacturing_data.cnt * rois.component_cnt
      ELSE NULL
    END AS component_cnt
  FROM
    recursive_order_item_specification rois,
    items AS i LEFT JOIN manufacturing_data ON (i.specification_number = manufacturing_data.manufacturing_number OR i.cover_number = manufacturing_data.manufacturing_number)
  WHERE i.id = rois.component_id
)
SELECT component_id, component_cnt FROM recursive_order_item_specification
WHERE component_id IS NOT NULL
),
item_total_cnt AS (
    SELECT component_id, SUM(component_cnt) AS cnt
    FROM all_components
    GROUP BY component_id
)
SELECT SUM(cnt * factor * base_payment * (unit_time + working_time + lead_time)) AS total_specification_route_cost
FROM item_total_cnt LEFT JOIN route_data
ON item_total_cnt.component_id = route_data.id
WHERE route_number IS NOT NULL;")[0]->total_specification_route_cost;
    }

    private function getCoverItemsRouteCost(Item $item)
    {
        return DB::select("WITH route_data AS (
SELECT items.id, route_point.route_number, route_point.point_number, route_point.point_code,
route_point.operation_code, route_point.rate_code, rates.factor,
route_point.unit_time, route_point.working_time, route_point.lead_time, points.base_payment
FROM items LEFT JOIN route_point ON items.route_number = route_point.route_number
LEFT JOIN points ON route_point.point_code = points.code
LEFT JOIN rates ON route_point.rate_code = rates.code
),

all_components AS (
WITH RECURSIVE manufacturing_data AS (
SELECT specification_cover_union.manufacturing_number, specification_cover_union.item_id,
specification_cover_union.cnt FROM
(
SELECT specification_item.specification_number AS manufacturing_number, specification_item.item_id, specification_item.cnt
FROM specification_item
UNION ALL
SELECT cover_item.cover_number, cover_item.item_id, cover_item.area * cover_item.consumption AS cnt FROM cover_item
) AS specification_cover_union
),
recursive_order_item_specification AS (
  SELECT
    i.id AS current_item_id,
    CASE
      WHEN i.cover_number IS NOT NULL THEN manufacturing_data.item_id
      ELSE NULL
    END AS component_id,
    CASE
      WHEN i.cover_number IS NOT NULL THEN manufacturing_data.cnt
      ELSE NULL
    END AS component_cnt
  FROM
    items AS i LEFT JOIN manufacturing_data ON i.cover_number = manufacturing_data.manufacturing_number
  WHERE
    i.id = $item->id

  UNION ALL

  SELECT
    i.id AS current_item_id,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL THEN manufacturing_data.item_id
      ELSE NULL
    END AS component_id,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL
      THEN manufacturing_data.cnt * rois.component_cnt
      ELSE NULL
    END AS component_cnt
  FROM
    recursive_order_item_specification rois,
    items AS i LEFT JOIN manufacturing_data ON (i.specification_number = manufacturing_data.manufacturing_number OR i.cover_number = manufacturing_data.manufacturing_number)
  WHERE i.id = rois.component_id
)
SELECT component_id, component_cnt FROM recursive_order_item_specification
WHERE component_id IS NOT NULL
),
item_total_cnt AS (
    SELECT component_id, SUM(component_cnt) AS cnt
    FROM all_components
    GROUP BY component_id
)
SELECT SUM(cnt * factor * base_payment * (unit_time + working_time + lead_time)) AS total_cover_route_cost
FROM item_total_cnt LEFT JOIN route_data
ON item_total_cnt.component_id = route_data.id
WHERE route_number IS NOT NULL;")[0]->total_cover_route_cost;
    }
}
