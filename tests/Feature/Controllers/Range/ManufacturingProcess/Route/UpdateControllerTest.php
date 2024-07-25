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
    public function a_route_can_be_updated()
    {
        $route = Route::has('points')->has('relatedItems')->whereNull('added_to_order_at')->first();

        $newData = [
            'number' => '20000000'
        ];

        $response = $this->patch("/routes/{$route->number}", $newData);

        $this->assertDatabaseHas('routes', $newData);

        $itemsWithCurrentRoute = Item::where('route_number', $route->number)->get();

        foreach ($itemsWithCurrentRoute as $itemWithCurrentRoute) {
            $this->assertEquals($newData['number'], $itemWithCurrentRoute->route_number);
        }

        foreach ($route->relatedItems as $relatedItem) {
            $updatedRecord = DB::table('item_route')
                ->where('item_id', $relatedItem->id)
                ->where('route_number', $newData['number'])
                ->first();

            $this->assertNotNull($updatedRecord);
        }

        foreach ($route->points as $routePoint) {
            $updatedRecord = DB::table('route_point')
                ->where('route_number', $newData['number'])
                ->where('point_number', $routePoint->pivot->point_number)
                ->first();

            $this->assertNotNull($updatedRecord);
        }

        $oldRoute = Route::find($route->number);
        $this->assertNull($oldRoute);

        $response->assertRedirect('/routes');

        $response = $this->get('/routes');
        $response->assertSee(__('messages.successful_update'));
    }
}
