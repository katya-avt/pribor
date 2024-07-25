<?php

namespace Tests\Feature\Controllers\Range\Item\ManufacturingProcess\Specification;

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
    public function a_specification_can_be_deleted_from_item_specification_list()
    {
        $item = Item::has('specifications')->first();
        $itemSpecification = $item->specifications->first();

        $response = $this->delete("/items/{$item->id}/specifications/{$itemSpecification->number}");

        $deletedRecord = DB::table('item_specification')
            ->where('item_id', $item->id)
            ->where('specification_number', $itemSpecification->number)
            ->first();

        $this->assertNull($deletedRecord);

        $response->assertRedirect("/items/{$item->id}/specifications");

        $response = $this->get("/items/{$item->id}/specifications");
        $response->assertSee(__('messages.successful_delete'));
    }
}
