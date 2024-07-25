<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Cover;

use App\Models\Range\Cover;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexControllerTest extends TestCase
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
    public function response_for_route_covers_index_is_view_range_manufacturing_process_covers_index_with_covers()
    {
        $covers = Cover::take(10)->get();

        $response = $this->get('/covers');
        $response->assertOk();

        $response->assertViewIs('range.manufacturing-process.covers.index');

        foreach ($covers as $cover) {
            $response->assertSee($cover->number);
        }
    }
}
