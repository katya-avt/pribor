<?php

namespace App\Services\Orders\Order\OrderItem;

use App\Models\Orders\Order;
use App\Models\Range\Department;
use Illuminate\Support\Facades\DB;

class ShowService
{
    public function getLaborIntensityAndProfitDistribution(Order $order)
    {
        $orderDepartmentsDistribution = self::getOrderDepartmentsDistribution($order);
        $allDepartments = Department::pluck('name');

        foreach ($allDepartments as $department) {
            if (!array_key_exists($department, $orderDepartmentsDistribution)) {
                $orderDepartmentsDistribution[$department] = 0;
            }
        }

        return $orderDepartmentsDistribution;
    }

    private function getOrderDepartmentsCnt(Order $order)
    {
        return DB::select("SELECT departments.name, COUNT(points.department_id) AS cnt
FROM order_item_route INNER JOIN points ON order_item_route.point_code = points.code
    INNER JOIN departments ON points.department_id = departments.id
        WHERE order_id = $order->id GROUP BY points.department_id, departments.name");
    }

    private function getTotalPointsCnt(Order $order)
    {
        $orderDepartmentsCnt = self::getOrderDepartmentsCnt($order);

        $totalPointsCnt = 0;

        foreach ($orderDepartmentsCnt as $department) {
            $totalPointsCnt += $department->cnt;
        }

        return $totalPointsCnt;
    }

    private function getOrderDepartmentsDistribution(Order $order)
    {
        $orderDepartmentsCnt = self::getOrderDepartmentsCnt($order);
        $totalPointsCnt = self::getTotalPointsCnt($order);

        $orderDepartmentsDistribution = [];

        foreach ($orderDepartmentsCnt as $department) {
            $orderDepartmentsDistribution[$department->name] =
                round(($department->cnt / $totalPointsCnt) * 100, 2);
        }
        return $orderDepartmentsDistribution;
    }
}
