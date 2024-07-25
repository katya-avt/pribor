<?php

namespace Tests\Feature\Controllers\Range\Item\CurrentSpecificationsAndRoute;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
    public function item_current_specifications_and_route_can_be_updated()
    {
        $item = Item::whereNotNull('specification_number')
            ->whereNotNull('cover_number')
            ->whereNotNull('route_number')
            ->first();

        $item->update([
            'specification_number' => null,
            'cover_number' => null,
            'route_number' => null
        ]);
        $item->refresh();

        $newData = [
            'specification_number' => $item->specifications()->first()->number,
            'cover_number' => $item->covers()->first()->number,
            'route_number' => $item->routes()->first()->number
        ];

        $response = $this->patch("/items/{$item->id}/current-specifications-and-route", $newData);
        $item->refresh();

        $this->assertDatabaseHas('items', ['id' => $item->id] + $newData);

        $response->assertRedirect("/items/{$item->id}");

        $response = $this->get("/items/{$item->id}");
        $response->assertSee(__('messages.successful_update'));
    }
}
