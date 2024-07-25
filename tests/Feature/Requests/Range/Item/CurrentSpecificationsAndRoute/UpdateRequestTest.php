<?php

namespace Tests\Feature\Requests\Range\Item\CurrentSpecificationsAndRoute;

use App\Models\Range\Group;
use App\Models\Range\Item;
use App\Models\Range\ItemType;
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
    public function request_should_fail_when_specification_number_is_not_provided_when_item_is_proprietary()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->where('item_type_id', ItemType::getItemTypeIdByItemTypeName(ItemType::PROPRIETARY))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $item->specifications()->detach();

        $data = [
            'specification_number' => null,
            'cover_number' => $item->cover_number,
            'route_number' => $item->route_number
        ];

        $from = $this->from("/items/{$item->id}/current-specifications-and-route/edit");

        $response = $from->patch("/items/{$item->id}/current-specifications-and-route", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_specification_number_is_provided_when_item_is_not_proprietary()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->where('item_type_id', ItemType::getItemTypeIdByItemTypeName(ItemType::TOLLING))
            ->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $data = [
            'specification_number' => 'Номер спецификации',
            'cover_number' => $item->cover_number,
            'route_number' => $item->route_number
        ];

        $from = $this->from("/items/{$item->id}/current-specifications-and-route/edit");

        $response = $from->patch("/items/{$item->id}/current-specifications-and-route", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_specification_number_is_not_selected_from_list()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->where('item_type_id', ItemType::getItemTypeIdByItemTypeName(ItemType::PROPRIETARY))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $data = [
            'specification_number' => 'Значение не из списка.',
            'cover_number' => $item->cover_number,
            'route_number' => $item->route_number
        ];

        $from = $this->from("/items/{$item->id}/current-specifications-and-route/edit");

        $response = $from->patch("/items/{$item->id}/current-specifications-and-route", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_cover_number_is_not_filled_in_when_there_is_a_list_of_values()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->where('item_type_id', ItemType::getItemTypeIdByItemTypeName(ItemType::PROPRIETARY))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $data = [
            'specification_number' => $item->specification_number,
            'cover_number' => null,
            'route_number' => $item->route_number
        ];

        $from = $this->from("/items/{$item->id}/current-specifications-and-route/edit");

        $response = $from->patch("/items/{$item->id}/current-specifications-and-route", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_cover_number_is_not_selected_from_list()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->where('item_type_id', ItemType::getItemTypeIdByItemTypeName(ItemType::PROPRIETARY))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $data = [
            'specification_number' => $item->specification_number,
            'cover_number' => 'Значение не из списка.',
            'route_number' => $item->route_number
        ];

        $from = $this->from("/items/{$item->id}/current-specifications-and-route/edit");

        $response = $from->patch("/items/{$item->id}/current-specifications-and-route", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_route_number_is_not_provided_when_item_is_tolling()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->where('item_type_id', ItemType::getItemTypeIdByItemTypeName(ItemType::TOLLING))
            ->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $item->routes()->detach();

        $data = [
            'specification_number' => null,
            'cover_number' => $item->cover_number,
            'route_number' => null
        ];

        $from = $this->from("/items/{$item->id}/current-specifications-and-route/edit");

        $response = $from->patch("/items/{$item->id}/current-specifications-and-route", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_route_number_is_not_filled_in_when_there_is_a_list_of_values()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->where('item_type_id', ItemType::getItemTypeIdByItemTypeName(ItemType::PROPRIETARY))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $data = [
            'specification_number' => $item->specification_number,
            'cover_number' => $item->cover_number,
            'route_number' => null
        ];

        $from = $this->from("/items/{$item->id}/current-specifications-and-route/edit");

        $response = $from->patch("/items/{$item->id}/current-specifications-and-route", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_route_number_is_not_filled_in_when_specification_given()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->where('item_type_id', ItemType::getItemTypeIdByItemTypeName(ItemType::PROPRIETARY))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $item->routes()->detach();

        $data = [
            'specification_number' => $item->specification_number,
            'cover_number' => $item->cover_number,
            'route_number' => null
        ];

        $from = $this->from("/items/{$item->id}/current-specifications-and-route/edit");

        $response = $from->patch("/items/{$item->id}/current-specifications-and-route", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_route_number_is_not_selected_from_list()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->where('item_type_id', ItemType::getItemTypeIdByItemTypeName(ItemType::PROPRIETARY))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $data = [
            'specification_number' => $item->specification_number,
            'cover_number' => $item->cover_number,
            'route_number' => 'Значение не из списка.',
        ];

        $from = $this->from("/items/{$item->id}/current-specifications-and-route/edit");

        $response = $from->patch("/items/{$item->id}/current-specifications-and-route", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
