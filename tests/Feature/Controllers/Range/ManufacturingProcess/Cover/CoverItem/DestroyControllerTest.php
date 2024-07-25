<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Cover\CoverItem;

use App\Models\Range\Cover;
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
    public function a_cover_item_can_be_deleted()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items()->first();

        $response = $this->delete("/covers/{$cover->number}/{$coverItem->id}");

        $deletedRecord = DB::table('cover_item')
            ->where('cover_number', $cover->number)
            ->where('item_id', $coverItem->id)
            ->first();

        $this->assertNull($deletedRecord);

        $response->assertRedirect("/covers/{$cover->number}");

        $response = $this->get("/covers/{$cover->number}");
        $response->assertSee(__('messages.successful_delete'));
    }
}
