<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Route;

use App\Models\Range\Item;
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
    public function a_route_can_be_deleted()
    {
        $route = Route::has('points')->has('relatedItems')->whereNull('added_to_order_at')->first();

        $response = $this->delete("/routes/{$route->number}");

        $itemsWithCurrentRoute = Item::where('route_number', $route->number)->get();

        foreach ($itemsWithCurrentRoute as $itemWithCurrentRoute) {
            $this->assertNull($itemWithCurrentRoute->route_number);
        }

        $deletedRecords = DB::table('item_route')
            ->where('route_number', $route->number)
            ->get()->isEmpty();

        $this->assertTrue($deletedRecords);

        $deletedRecords = DB::table('route_point')
            ->where('route_number', $route->number)
            ->get()->isEmpty();

        $this->assertTrue($deletedRecords);

        $deletedRoute = Route::find($route->number);
        $this->assertNull($deletedRoute);

        $response->assertRedirect("/routes");

        $response = $this->get("/routes");
        $response->assertSee(__('messages.successful_delete'));
    }
}
