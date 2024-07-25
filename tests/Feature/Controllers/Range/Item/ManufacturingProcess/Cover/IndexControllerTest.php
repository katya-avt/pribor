<?php

namespace Tests\Feature\Controllers\Range\Item\ManufacturingProcess\Cover;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexControllerTest extends TestCase
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
    public function response_for_route_items_covers_index_is_view_range_items_manufacturing_process_covers_index_with_item_covers()
    {
        $item = Item::has('covers')->first();

        $response = $this->get("/items/{$item->id}/covers");
        $response->assertOk();

        $response->assertViewIs('range.items.manufacturing-process.covers.index');

        foreach ($item->covers as $itemCover) {
            $response->assertSee($itemCover->number);
        }
    }
}
