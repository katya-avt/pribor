<?php

namespace Tests\Feature\Controllers\ItemAvailabilityAndConsumption\Availability;

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

        $user = User::where('role_id', Role::PROCUREMENT_AND_SALES_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function an_item_cnt_can_be_updated()
    {
        $item = Item::all()->random();

        $newData = [
            'cnt' => 20
        ];

        $response = $this->patch("/availability/{$item->id}", $newData);
        $item->refresh();

        $this->assertDatabaseHas('items', ['id' => $item->id] + $newData);

        $response->assertRedirect('/availability');

        $response = $this->get('/availability');
        $response->assertSee(__('messages.successful_update'));
    }
}
