<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Route\RoutePoint;

use App\Models\Range\Route;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreControllerTest extends TestCase
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
    public function a_route_point_can_be_stored()
    {
        $route = Route::create([
            'number' => '10000000'
        ]);

        $data = [
            'point_code' => '1.1',
            'operation_code' => '0101',
            'unit_time' => 14.77,
            'working_time' => 2.29,
            'lead_time' => 4.19,
            'rate_code' => 'С3'
        ];

        $response = $this->post("/routes/{$route->number}", $data);

        $storedRecord = DB::table('route_point')
            ->where('route_number', $route->number)
            ->where('point_number', 1)
            ->first();

        $this->assertNotNull($storedRecord);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $storedRecord->{$key});
        }

        $response->assertRedirect("/routes/{$route->number}");

        $response = $this->get("/routes/{$route->number}");
        $response->assertSee(__('messages.successful_store'));
    }
}
