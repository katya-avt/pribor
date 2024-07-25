<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem;

use App\Models\Range\Specification;
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
    public function a_specification_item_can_be_deleted()
    {
        $specification = Specification::has('items')->whereNull('added_to_order_at')->first();
        $specificationItem = $specification->items()->first();

        $response = $this->delete("/specifications/{$specification->number}/{$specificationItem->id}");

        $deletedRecord = DB::table('specification_item')
            ->where('specification_number', $specification->number)
            ->where('item_id', $specificationItem->id)
            ->first();

        $this->assertNull($deletedRecord);

        $response->assertRedirect("/specifications/{$specification->number}");

        $response = $this->get("/specifications/{$specification->number}");
        $response->assertSee(__('messages.successful_delete'));
    }
}
