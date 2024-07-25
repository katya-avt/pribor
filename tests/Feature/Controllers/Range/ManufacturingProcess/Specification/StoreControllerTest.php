<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Specification;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreControllerTest extends TestCase
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
    public function a_specification_can_be_stored()
    {
        $data = [
            'number' => '10000000'
        ];

        $response = $this->post("/specifications", $data);

        $this->assertDatabaseHas('specifications', $data);

        $response->assertRedirect('/specifications');

        $response = $this->get('/specifications');
        $response->assertSee(__('messages.successful_store'));
    }
}
