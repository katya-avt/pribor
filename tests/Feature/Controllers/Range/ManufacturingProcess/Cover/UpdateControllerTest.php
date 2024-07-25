<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Cover;

use App\Models\Range\Cover;
use App\Models\Range\Item;
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
    public function a_cover_can_be_updated()
    {
        $cover = Cover::has('items')->has('relatedItems')->whereNull('added_to_order_at')->first();

        $newData = [
            'number' => '20000000'
        ];

        $response = $this->patch("/covers/{$cover->number}", $newData);

        $this->assertDatabaseHas('covers', $newData);

        $itemsWithCurrentCover = Item::where('cover_number', $cover->number)->get();

        foreach ($itemsWithCurrentCover as $itemWithCurrentCover) {
            $this->assertEquals($newData['number'], $itemWithCurrentCover->cover_number);
        }

        foreach ($cover->relatedItems as $relatedItem) {
            $updatedRecord = DB::table('item_cover')
                ->where('item_id', $relatedItem->id)
                ->where('cover_number', $newData['number'])
                ->first();

            $this->assertNotNull($updatedRecord);
        }

        foreach ($cover->items as $coverItem) {
            $updatedRecord = DB::table('cover_item')
                ->where('cover_number', $newData['number'])
                ->where('item_id', $coverItem)
                ->first();

            $this->assertNotNull($updatedRecord);
        }

        $oldCover = Cover::find($cover->number);
        $this->assertNull($oldCover);

        $response->assertRedirect('/covers');

        $response = $this->get('/covers');
        $response->assertSee(__('messages.successful_update'));
    }
}
