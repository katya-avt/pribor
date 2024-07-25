<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Route\RoutePoint;

use App\Models\Range\Point;
use App\Models\Range\Route;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function a_route_point_can_be_updated()
    {
        $route = Route::has('points')->whereNull('added_to_order_at')->first();
        $routePoint = $route->points()->first();

        $newRoutePoint = Point::where('code', '<>', $routePoint->code)->first();

        $newData = [
            'point_code' => $newRoutePoint->code,
            'operation_code' => '0101',
            'unit_time' => 14.77,
            'working_time' => 2.29,
            'lead_time' => 4.19,
            'rate_code' => 'С3'
        ];

        $response = $this->patch("/routes/{$route->number}/{$routePoint->pivot->point_number}", $newData);

        $updatedRecord = DB::table('route_point')
            ->where('route_number', $route->number)
            ->where('point_number', 1)
            ->first();

        $this->assertNotNull($updatedRecord);

        foreach ($newData as $key => $value) {
            $this->assertEquals($value, $updatedRecord->{$key});
        }

        $response->assertRedirect("/routes/{$route->number}");

        $response = $this->get("/routes/{$route->number}");
        $response->assertSee(__('messages.successful_update'));
    }
}
