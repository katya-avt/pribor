<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Cover;

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
    public function response_for_covers_edit_is_view_range_manufacturing_process_covers_edit_with_update_form()
    {
        $cover = Cover::whereNull('added_to_order_at')->first();

        $response = $this->get("/covers/{$cover->number}/edit");
        $response->assertOk();

        $response->assertViewIs('range.manufacturing-process.covers.edit');

        $response->assertSee($cover->number);
    }
}
