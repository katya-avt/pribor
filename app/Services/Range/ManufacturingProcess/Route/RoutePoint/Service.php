<?php

namespace App\Services\Range\ManufacturingProcess\Route\RoutePoint;

use App\Models\Range\Route;
use Illuminate\Support\Facades\DB;

class Service
{
    public function store(Route $route, $newRoutePointData): string
    {
        try {
            DB::beginTransaction();

            $pointCount = $route->points->isNotEmpty() ? $route->points()->count() : 0;
            $nextPointNumber = $pointCount + 1;

            $routePointData = array_diff_key($newRoutePointData, ['point_code' => null]) + ['point_number' => $nextPointNumber];

            $route->points()
                ->attach($newRoutePointData['point_code'], $routePointData);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_store');
    }

    public function update(Route $route, $pointNumber, $newRoutePointData)
    {
        try {
            DB::beginTransaction();

            DB::table('route_point')
                ->where('route_number', $route->number)
                ->where('point_number', $pointNumber)
                ->update($newRoutePointData);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }

    public function rearrange($route, $order)
    {
        try {
            DB::beginTransaction();

            $pointCount = $route->points()->count();

            foreach ($order as $currentNumber => $newNumber) {
                $route->points()
                    ->where('route_number', $route->number)
                    ->where('point_number', $currentNumber)
                    ->update(['point_number' => $newNumber + $pointCount]);
            }

            foreach ($order as $newNumber) {
                $route->points()
                    ->where('route_number', $route->number)
                    ->where('point_number', $newNumber + $pointCount)
                    ->update(['point_number' => $newNumber]);
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }

    public function delete(Route $route, $pointNumber)
    {
        try {
            DB::beginTransaction();

            DB::table('route_point')
                ->where('route_number', $route->number)
                ->where('point_number', $pointNumber)
                ->delete();

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_delete');
    }
}
