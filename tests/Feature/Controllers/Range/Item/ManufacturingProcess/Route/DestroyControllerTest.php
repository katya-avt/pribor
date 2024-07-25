<?php

namespace Tests\Feature\Controllers\Range\Item\ManufacturingProcess\Route;

use App\Models\Range\Item;
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
    public function a_route_can_be_deleted_from_item_route_list()
    {
        $item = Item::has('routes')->first();
        $itemRoute = $item->routes->first();

        $response = $this->delete("/items/{$item->id}/routes/{$itemRoute->number}");

        $deletedRecord = DB::table('item_route')
            ->where('item_id', $item->id)
            ->where('route_number', $itemRoute->number)
            ->first();

        $this->assertNull($deletedRecord);

        $response->assertRedirect("/items/{$item->id}/routes");

        $response = $this->get("/items/{$item->id}/routes");
        $response->assertSee(__('messages.successful_delete'));
    }
}
