<?php

namespace Tests\Feature\Requests\Range\Item\ManufacturingProcess\Specification;

use App\Models\Range\Group;
use App\Models\Range\Item;
use App\Models\Range\Specification;
use App\Models\Range\SpecificationItem;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreRequestTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function request_should_fail_when_specification_number_is_not_provided()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $data = [
            'number' => null
        ];

        $from = $this->from("/items/{$item->id}/specifications/create");

        $response = $from->post("/items/{$item->id}/specifications", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_specification_number_is_not_selected_from_list()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $data = [
            'number' => 'Значение не из списка.'
        ];

        $from = $this->from("/items/{$item->id}/specifications/create");

        $response = $from->post("/items/{$item->id}/specifications", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_specification_number_is_already_on_list()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $data = [
            'number' => $item->specification_number
        ];

        $from = $this->from("/items/{$item->id}/specifications/create");

        $response = $from->post("/items/{$item->id}/specifications", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_specification_number_is_empty()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $specification = Specification::find($item->specification_number);
        $specification->items()->detach();

        $data = [
            'number' => $item->specification_number
        ];

        $from = $this->from("/items/{$item->id}/specifications/create");

        $response = $from->post("/items/{$item->id}/specifications", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_specification_number_contain_the_item_for_which_it_is_selected()
    {
        $specification = Specification::has('items')->whereHas('relatedItems', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL));
        })->first();

        $relatedItem = $specification->relatedItems->first();

        $data = [
            'number' => $specification->number
        ];

        $from = $this->from("/items/{$relatedItem->id}/specifications/create");

        $response = $from->post("/items/{$relatedItem->id}/specifications", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_for_detail_specification_number_contains_not_metal_or_plastic()
    {
        $detailItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))->first();

        $specification = Specification::whereHas('items', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::ASSEMBLY_ITEM));
        })->first();

        $data = [
            'number' => $specification->number
        ];

        $from = $this->from("/items/{$detailItem->id}/specifications/create");

        $response = $from->post("/items/{$detailItem->id}/specifications", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_for_assembly_item_specification_number_contains_incorrect_components()
    {
        $assemblyItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::ASSEMBLY_ITEM))->first();

        $specification = Specification::whereHas('items', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::METAL));
        })->first();

        $data = [
            'number' => $specification->number
        ];

        $from = $this->from("/items/{$assemblyItem->id}/specifications/create");

        $response = $from->post("/items/{$assemblyItem->id}/specifications", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_for_galvanic_cover_specification_number_contains_not_metal_or_chemical()
    {
        $galvanicItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::GALVANIC))->first();

        $specification = Specification::whereHas('items', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::ASSEMBLY_ITEM));
        })->first();

        $data = [
            'number' => $specification->number
        ];

        $from = $this->from("/items/{$galvanicItem->id}/specifications/create");

        $response = $from->post("/items/{$galvanicItem->id}/specifications", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_for_detail_specification_number_contains_more_than_one_element()
    {
        $specificationData = [
            'number' => '10000000'
        ];

        $specification = Specification::create($specificationData);

        $firstMetalItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::METAL))->first();

        SpecificationItem::factory()->create([
            'specification_number' => $specification->number,
            'item_id' => $firstMetalItem->id
        ]);

        $secondMetalItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::METAL))
            ->where('id', '<>', $firstMetalItem->id)
            ->first();

        SpecificationItem::factory()->create([
            'specification_number' => $specification->number,
            'item_id' => $secondMetalItem->id
        ]);

        $detailItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))->first();

        $data = [
            'number' => $specification->number
        ];

        $from = $this->from("/items/{$detailItem->id}/specifications/create");

        $response = $from->post("/items/{$detailItem->id}/specifications", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
