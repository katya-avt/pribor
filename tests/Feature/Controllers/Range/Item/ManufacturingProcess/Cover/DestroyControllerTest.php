<?php

namespace Tests\Feature\Controllers\Range\Item\ManufacturingProcess\Cover;

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
    public function a_cover_can_be_deleted_from_item_cover_list()
    {
        $item = Item::has('covers')->first();
        $itemCover = $item->covers->first();

        $response = $this->delete("/items/{$item->id}/covers/{$itemCover->number}");

        $deletedRecord = DB::table('item_cover')
            ->where('item_id', $item->id)
            ->where('cover_number', $itemCover->number)
            ->first();

        $this->assertNull($deletedRecord);

        $response->assertRedirect("/items/{$item->id}/covers");

        $response = $this->get("/items/{$item->id}/covers");
        $response->assertSee(__('messages.successful_delete'));
    }
}
