<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Cover\CoverItem;

use App\Models\Range\Cover;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateControllerTest extends TestCase
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
    public function response_for_route_covers_cover_items_create_is_view_range_manufacturing_process_covers_cover_items_create_with_store_form()
    {
        $cover = Cover::whereNull('added_to_order_at')->first();

        $response = $this->get("/covers/{$cover->number}/create");
        $response->assertOk();

        $response->assertViewIs('range.manufacturing-process.covers.cover-items.create');
    }
}
