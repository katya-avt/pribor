<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Cover;

use App\Models\Range\Cover;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowControllerTest extends TestCase
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
    public function response_for_route_covers_show_is_view_range_manufacturing_process_covers_show_with_single_cover()
    {
        $cover = Cover::has('items')->first();

        $response = $this->get("/covers/{$cover->number}");
        $response->assertOk();

        $response->assertViewIs('range.manufacturing-process.covers.show');

        $response->assertSee($cover->number);

        foreach ($cover->items as $coverItem) {
            $response->assertSee($coverItem->group->name);
            $response->assertSee($coverItem->drawing);
            $response->assertSee($coverItem->name);
            $response->assertSee($coverItem->pivot->area);
            $response->assertSee($coverItem->pivot->consumption);
            $response->assertSee($coverItem->unit->short_name);
        }
    }
}
