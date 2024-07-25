<?php

namespace Tests\Feature\Controllers\Range\Item\ManufacturingProcess\Route;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
    public function a_route_can_be_stored_to_item_route_list()
    {
        $detailItem = Item::has('detail')->first();
        $secondDetailItem = Item::has('detail')->whereNotNull('route_number')
            ->where('id', '<>', $detailItem->id)->first();

        $data = [
            'number' => $secondDetailItem->route_number
        ];

        $response = $this->post("/items/{$detailItem->id}/routes", $data);

        $this->assertDatabaseHas('item_route', [
            'item_id' => $detailItem->id,
            'route_number' => $data['number']
        ]);

        $response->assertRedirect("/items/{$detailItem->id}/routes");

        $response = $this->get("/items/{$detailItem->id}/routes");
        $response->assertSee(__('messages.successful_store'));
    }
}
