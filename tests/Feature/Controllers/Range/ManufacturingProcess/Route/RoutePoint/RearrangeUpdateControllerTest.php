<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Route\RoutePoint;

use App\Models\Range\Route;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RearrangeUpdateControllerTest extends TestCase
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
    public function a_route_point_can_be_rearranged()
    {
        $route = Route::create([
            'number' => '10000000'
        ]);

        $routePoint1 = [
            'point_code' => 'ЛазРез',
            'operation_code' => '0101',
            'unit_time' => 14.77,
            'working_time' => 2.29,
            'lead_time' => 4.19,
            'rate_code' => 'С3'
        ];

        $response = $this->post("/routes/{$route->number}", $routePoint1);

        $routePoint2 = [
            'point_code' => '1.7',
            'operation_code' => '0101',
            'unit_time' => 14.77,
            'working_time' => 2.29,
            'lead_time' => 4.19,
            'rate_code' => 'С3'
        ];

        $response = $this->post("/routes/{$route->number}", $routePoint2);

        $routePoint3 = [
            'point_code' => '1.2',
            'operation_code' => '0101',
            'unit_time' => 14.77,
            'working_time' => 2.29,
            'lead_time' => 4.19,
            'rate_code' => 'С3'
        ];

        $response = $this->post("/routes/{$route->number}", $routePoint3);

        $routePoint4 = [
            'point_code' => 'Уп',
            'operation_code' => '0101',
            'unit_time' => 14.77,
            'working_time' => 2.29,
            'lead_time' => 4.19,
            'rate_code' => 'С3'
        ];

        $response = $this->post("/routes/{$route->number}", $routePoint4);

        $data = [
            'order' => [
                '1' => '2',
                '2' => '4',
                '3' => '3',
                '4' => '1'
            ]
        ];

        $response = $this->patch("/routes/{$route->number}/rearrange", $data);

        $rearrangedRecords = DB::table('route_point')
            ->where('route_number', $route->number)
            ->get()->pluck('point_code');

        $this->assertEquals($routePoint1['point_code'], $rearrangedRecords[1]);
        $this->assertEquals($routePoint2['point_code'], $rearrangedRecords[3]);
        $this->assertEquals($routePoint3['point_code'], $rearrangedRecords[2]);
        $this->assertEquals($routePoint4['point_code'], $rearrangedRecords[0]);

        $response->assertRedirect("/routes/{$route->number}");

        $response = $this->get("/routes/{$route->number}");
        $response->assertSee(__('messages.successful_update'));
    }
}
