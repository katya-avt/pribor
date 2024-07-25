<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Cover\CoverItem;

use App\Models\Range\Cover;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditControllerTest extends TestCase
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
    public function response_for_covers_cover_items_edit_is_view_range_manufacturing_process_covers_cover_items_edit_with_update_form()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items()->first();

        $response = $this->get("/covers/{$cover->number}/{$coverItem->id}/edit");
        $response->assertOk();

        $response->assertViewIs('range.manufacturing-process.covers.cover-items.edit');

        $response->assertSee($coverItem->drawing);
        $response->assertSee($coverItem->pivot->area);
        $response->assertSee($coverItem->pivot->consumption);
    }
}
