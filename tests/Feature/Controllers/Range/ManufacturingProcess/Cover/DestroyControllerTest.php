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
    public function a_cover_can_be_deleted()
    {
        $cover = Cover::has('items')->has('relatedItems')->whereNull('added_to_order_at')->first();

        $response = $this->delete("/covers/{$cover->number}");

        $itemsWithCurrentCover = Item::where('cover_number', $cover->number)->get();

        foreach ($itemsWithCurrentCover as $itemWithCurrentCover) {
            $this->assertNull($itemWithCurrentCover->cover_number);
        }

        $deletedRecords = DB::table('item_cover')
            ->where('cover_number', $cover->number)
            ->get()->isEmpty();

        $this->assertTrue($deletedRecords);

        $deletedRecords = DB::table('cover_item')
            ->where('cover_number', $cover->number)
            ->get()->isEmpty();

        $this->assertTrue($deletedRecords);

        $deletedCover = Cover::find($cover->number);
        $this->assertNull($deletedCover);

        $response->assertRedirect("/covers");

        $response = $this->get("/covers");
        $response->assertSee(__('messages.successful_delete'));
    }
}
