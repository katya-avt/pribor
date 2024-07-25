<?php

namespace Tests\Feature\Requests\Range\ManufacturingProcess\Specification\SpecificationItem;

use App\Models\Range\Group;
use App\Models\Range\Item;
use App\Models\Range\Specification;
use App\Models\Range\SpecificationItem;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateRequestTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function request_should_fail_when_drawing_is_not_provided()
    {
        $specification = Specification::has('items')->whereNull('added_to_order_at')->first();
        $specificationItem = $specification->items->first();

        $data = [
            'drawing' => null,
            'cnt' => 2
        ];

        $from = $this->from("/specifications/{$specification->number}/{$specificationItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$specificationItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_drawing_is_already_on_list()
    {
        $specificationData = [
            'number' => '10000000'
        ];

        $specification = Specification::create($specificationData);

        $firstDetailItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))->first();

        SpecificationItem::factory()->create([
            'specification_number' => $specification->number,
            'item_id' => $firstDetailItem->id
        ]);

        $secondDetailItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->where('items.id', '<>', $firstDetailItem->id)
            ->first();

        SpecificationItem::factory()->create([
            'specification_number' => $specification->number,
            'item_id' => $secondDetailItem->id
        ]);

        $data = [
            'drawing' => $secondDetailItem->drawing,
            'cnt' => 2
        ];

        $from = $this->from("/specifications/{$specification->number}/{$firstDetailItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$firstDetailItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_not_fail_when_unique_drawing_is_not_updated()
    {
        $specification = Specification::has('items')->whereNull('added_to_order_at')->first();
        $specificationItem = $specification->items->first();

        $data = [
            'drawing' => $specificationItem->drawing,
            'cnt' => 2
        ];

        $from = $this->from("/specifications/{$specification->number}/{$specificationItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$specificationItem->id}", $data);
        $response->assertRedirect("/specifications/{$specification->number}");
    }

    /** @test */
    public function request_should_fail_when_drawing_is_not_selected_from_list()
    {
        $specification = Specification::has('items')->whereNull('added_to_order_at')->first();
        $specificationItem = $specification->items->first();

        $data = [
            'drawing' => 'Значение не из списка.',
            'cnt' => 2
        ];

        $from = $this->from("/specifications/{$specification->number}/{$specificationItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$specificationItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_item_is_cover()
    {
        $specification = Specification::has('items')->whereNull('added_to_order_at')->first();
        $specificationItem = $specification->items->first();

        $paintItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::PAINT))->first();

        $data = [
            'drawing' => $paintItem->drawing,
            'cnt' => 2
        ];

        $from = $this->from("/specifications/{$specification->number}/{$specificationItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$specificationItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_specification_has_related_items_and_contain_the_item_for_which_it_is_filled_in()
    {
        $specification = Specification::has('items')->has('relatedItems')->whereNull('added_to_order_at')->first();
        $specificationItem = $specification->items->first();
        $relatedItem = $specification->relatedItems->first();

        $data = [
            'drawing' => $relatedItem->drawing,
            'cnt' => 2
        ];

        $from = $this->from("/specifications/{$specification->number}/{$specificationItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$specificationItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_specification_has_related_detail_items_and_contains_not_metal_or_plastic()
    {
        $specification = Specification::has('items')->whereHas('relatedItems', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL));
        })->whereNull('added_to_order_at')->first();

        $specificationItem = $specification->items->first();

        $assemblyItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::ASSEMBLY_ITEM))
            ->first();

        $data = [
            'drawing' => $assemblyItem->drawing,
            'cnt' => 2
        ];

        $from = $this->from("/specifications/{$specification->number}/{$specificationItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$specificationItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_specification_has_related_assembly_items_and_contains_incorrect_components()
    {
        $specification = Specification::has('items')->whereHas('relatedItems', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::ASSEMBLY_ITEM));
        })->whereNull('added_to_order_at')->first();

        $specificationItem = $specification->items->first();

        $metalItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::METAL))
            ->first();

        $data = [
            'drawing' => $metalItem->drawing,
            'cnt' => 2
        ];

        $from = $this->from("/specifications/{$specification->number}/{$specificationItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$specificationItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_specification_has_related_galvanic_covers_and_contains_not_metal_or_chemical()
    {
        $specification = Specification::has('items')->whereHas('relatedItems', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::GALVANIC));
        })->whereNull('added_to_order_at')->first();

        $specificationItem = $specification->items->first();

        $assemblyItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::ASSEMBLY_ITEM))
            ->first();

        $data = [
            'drawing' => $assemblyItem->drawing,
            'cnt' => 2
        ];

        $from = $this->from("/specifications/{$specification->number}/{$specificationItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$specificationItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_cnt_is_not_provided()
    {
        $specification = Specification::has('items')->whereNull('added_to_order_at')->first();
        $specificationItem = $specification->items->first();

        $data = [
            'drawing' => $specificationItem->drawing,
            'cnt' => null
        ];

        $from = $this->from("/specifications/{$specification->number}/{$specificationItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$specificationItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_cnt_is_not_a_number()
    {
        $specification = Specification::has('items')->whereNull('added_to_order_at')->first();
        $specificationItem = $specification->items->first();

        $data = [
            'drawing' => $specificationItem->drawing,
            'cnt' => 'Не число.'
        ];

        $from = $this->from("/specifications/{$specification->number}/{$specificationItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$specificationItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_cnt_less_than_0()
    {
        $specification = Specification::has('items')->whereNull('added_to_order_at')->first();
        $specificationItem = $specification->items->first();

        $data = [
            'drawing' => $specificationItem->drawing,
            'cnt' => -2
        ];

        $from = $this->from("/specifications/{$specification->number}/{$specificationItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$specificationItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    public function request_should_fail_when_cnt_greater_than_99()
    {
        $specification = Specification::has('items')->whereNull('added_to_order_at')->first();
        $specificationItem = $specification->items->first();

        $data = [
            'drawing' => $specificationItem->drawing,
            'cnt' => 1000
        ];

        $from = $this->from("/specifications/{$specification->number}/{$specificationItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$specificationItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_quantity_for_unit_items_is_not_integer()
    {
        $specificationData = [
            'number' => '10000000'
        ];

        $specification = Specification::create($specificationData);

        $detailItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))->first();

        SpecificationItem::factory()->create([
            'specification_number' => $specification->number,
            'item_id' => $detailItem->id
        ]);

        $data = [
            'drawing' => $detailItem->drawing,
            'cnt' => 2.5,
        ];

        $from = $this->from("/specifications/{$specification->number}/{$detailItem->id}/edit");

        $response = $from->patch("/specifications/{$specification->number}/{$detailItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
