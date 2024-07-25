<?php

namespace Tests\Feature\Filters;

use App\Http\Filters\RouteFilter;
use App\Models\Range\Route;
use App\Models\Range\RoutePoint;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RouteFilterTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function filterProvider()
    {
        return [
            'routes_can_be_filtered_by_number' => [
                'data' => [
                    'search' => '1234',
                    'point_code' => null,
                    'operation_code' => null
                ],
                'includedRoute' => '00001234',
                'excludedRoute' => '00005678'
            ],

            'routes_can_be_filtered_by_contained_point' => [
                'data' => [
                    'search' => null,
                    'point_code' => 'ЛазРез',
                    'operation_code' => null
                ],
                'includedRoute' => '00005678',
                'excludedRoute' => '00001234'
            ],

            'routes_can_be_filtered_by_contained_operation' => [
                'data' => [
                    'search' => null,
                    'point_code' => null,
                    'operation_code' => '0102'
                ],
                'includedRoute' => '00005678',
                'excludedRoute' => '00001234'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider filterProvider
     * @param array $mockedFilterData
     * @param string $includedRoute
     * @param string $excludedRoute
     */
    public function filter_results_as_expected($mockedFilterData, $includedRoute, $excludedRoute)
    {
        $firstRoute = Route::create([
            'number' => '00001234'
        ]);

        $secondRoute = Route::create([
            'number' => '00005678'
        ]);

        RoutePoint::create([
            'route_number' => $secondRoute->number,
            'point_number' => 1,
            'point_code' => 'ЛазРез',
            'operation_code' => '0102',
            'rate_code' => 'С1',
            'unit_time' => 5,
            'working_time' => 5,
            'lead_time' => 5
        ]);

        $filter = app()->make(RouteFilter::class, ['queryParams' => array_filter($mockedFilterData)]);

        $routes = Route::with('points', 'operations')->filter($filter)->get();

        $includedRoute = Route::find($includedRoute);
        $excludedRoute = Route::find($excludedRoute);

        $this->assertTrue($routes->contains($includedRoute));
        $this->assertFalse($routes->contains($excludedRoute));
    }
}
