<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Route\RoutePoint;

use App\Models\Range\Route;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyControllerTest extends TestCase
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
    public function a_route_point_can_be_deleted()
    {
        $route = Route::has('points')->whereNull('added_to_order_at')->first();
        $routePoint = $route->points()->first();

        $response = $this->delete("/routes/{$route->number}/{$routePoint->pivot->point_number}");

        $deletedRecord = DB::table('route_point')
            ->where('route_number', $route->number)
            ->where('point_number', $routePoint->pivot->point_number)
            ->first();

        $this->assertNull($deletedRecord);

        $response->assertRedirect("/routes/{$route->number}");

        $response = $this->get("/routes/{$route->number}");
        $response->assertSee(__('messages.successful_delete'));
    }
}
