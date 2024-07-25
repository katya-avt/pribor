<?php

namespace Tests\Feature\Controllers\Admin\MarkedForDeletion\Item;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RestoreControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ADMIN)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function a_marked_for_deletion_item_can_be_restored_when_it_is_not_cover()
    {
        $item = Item::has('relatedSpecifications')->first();

        $item->relatedSpecifications()->update([
            'valid_to' => now()
        ]);

        $item->delete();

        $response = $this->patch("/admin/marked-for-deletion/items/{$item->id}");
        $item->refresh();

        $restoredItem = Item::find($item->id);
        $this->assertNotNull($restoredItem);

        foreach ($item->specifications as $itemSpecification) {
            $this->assertNull($itemSpecification->valid_to);
        }

        $response->assertRedirect('/admin/marked-for-deletion/items');

        $response = $this->get('/admin/marked-for-deletion/items');
        $response->assertSee(__('messages.successful_restore'));
    }

    /** @test */
    public function a_marked_for_deletion_item_can_be_restored_when_it_is_cover()
    {
        $item = Item::has('relatedCovers')->first();

        $item->relatedCovers()->update([
            'valid_to' => now()
        ]);

        $item->delete();

        $response = $this->patch("/admin/marked-for-deletion/items/{$item->id}");
        $item->refresh();

        $restoredItem = Item::find($item->id);
        $this->assertNotNull($restoredItem);

        foreach ($item->covers as $itemCover) {
            $this->assertNull($itemCover->valid_to);
        }

        $response->assertRedirect('/admin/marked-for-deletion/items');

        $response = $this->get('/admin/marked-for-deletion/items');
        $response->assertSee(__('messages.successful_restore'));
    }
}
