<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Cover\CoverItem;

use App\Models\Range\Cover;
use App\Models\Range\Group;
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
    public function a_cover_item_can_be_updated()
    {
        $cover = Cover::has('items', '=', 1)->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items()->first();

        $newCoverItem = Item::whereHas('group', function ($query) {
            $query->where('groups.name', Group::PAINT);
        })->where('id', '<>', $coverItem->id)->first();

        $newData = [
            'drawing' => $newCoverItem->drawing,
            'area' => 151.86,
            'consumption' => 0.00007
        ];

        $response = $this->patch("/covers/{$cover->number}/{$coverItem->id}", $newData);

        $updatedRecord = DB::table('cover_item')
            ->where('cover_number', $cover->number)
            ->where('item_id', $newCoverItem->id)
            ->first();

        $this->assertNotNull($updatedRecord);
        $this->assertEquals($newData['area'], $updatedRecord->area);
        $this->assertEquals($newData['consumption'], $updatedRecord->consumption);

        $response->assertRedirect("/covers/{$cover->number}");

        $response = $this->get("/covers/{$cover->number}");
        $response->assertSee(__('messages.successful_update'));
    }
}
