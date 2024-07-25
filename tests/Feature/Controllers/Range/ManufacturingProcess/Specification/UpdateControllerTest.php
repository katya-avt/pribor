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
    public function a_specification_can_be_updated()
    {
        $specification = Specification::has('items')->has('relatedItems')->whereNull('added_to_order_at')->first();

        $newData = [
            'number' => '20000000'
        ];

        $response = $this->patch("/specifications/{$specification->number}", $newData);

        $this->assertDatabaseHas('specifications', $newData);

        $itemsWithCurrentSpecification = Item::where('specification_number', $specification->number)->get();

        foreach ($itemsWithCurrentSpecification as $itemWithCurrentSpecification) {
            $this->assertEquals($newData['number'], $itemWithCurrentSpecification->specification_number);
        }

        foreach ($specification->relatedItems as $relatedItem) {
            $updatedRecord = DB::table('item_specification')
                ->where('item_id', $relatedItem->id)
                ->where('specification_number', $newData['number'])
                ->first();

            $this->assertNotNull($updatedRecord);
        }

        foreach ($specification->items as $specificationItem) {
            $updatedRecord = DB::table('specification_item')
                ->where('specification_number', $newData['number'])
                ->where('item_id', $specificationItem)
                ->first();

            $this->assertNotNull($updatedRecord);
        }

        $oldSpecification = Specification::find($specification->number);
        $this->assertNull($oldSpecification);

        $response->assertRedirect('/specifications');

        $response = $this->get('/specifications');
        $response->assertSee(__('messages.successful_update'));
    }
}
