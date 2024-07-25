<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Specification;

use App\Models\Range\Item;
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
    public function a_specification_can_be_deleted()
    {
        $specification = Specification::has('items')->has('relatedItems')->whereNull('added_to_order_at')->first();

        $response = $this->delete("/specifications/{$specification->number}");

        $itemsWithCurrentSpecification = Item::where('specification_number', $specification->number)->get();

        foreach ($itemsWithCurrentSpecification as $itemWithCurrentSpecification) {
            $this->assertNull($itemWithCurrentSpecification->specification_number);
        }

        $deletedRecords = DB::table('item_specification')
            ->where('specification_number', $specification->number)
            ->get()->isEmpty();

        $this->assertTrue($deletedRecords);

        $deletedRecords = DB::table('specification_item')
            ->where('specification_number', $specification->number)
            ->get()->isEmpty();

        $this->assertTrue($deletedRecords);

        $deletedSpecification = Specification::find($specification->number);
        $this->assertNull($deletedSpecification);

        $response->assertRedirect("/specifications");

        $response = $this->get("/specifications");
        $response->assertSee(__('messages.successful_delete'));
    }
}
