<?php

namespace Tests\Feature\Requests\Range\Item\Destroy;

use App\Models\Range\Group;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ComponentStoreRequestTest extends TestCase
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
        $item = Item::whereHas('relatedSpecifications', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL));
        })->first();

        $data = [
            'drawing' => null,
            'factor' => 1
        ];

        $from = $this->from("/items/{$item->id}/confirm-component-replacement");

        $response = $from->post("/items/{$item->id}/confirm-component-replacement", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_drawing_is_not_selected_from_list()
    {
        $item = Item::whereHas('relatedSpecifications', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL));
        })->first();

        $data = [
            'drawing' => 'Значение не из списка.',
            'factor' => 1
        ];

        $from = $this->from("/items/{$item->id}/confirm-component-replacement");

        $response = $from->post("/items/{$item->id}/confirm-component-replacement", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_item_is_not_from_the_same_group()
    {
        $detailItem = Item::whereHas('relatedSpecifications', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL));
        })->first();

        $fastenerItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::FASTENER))->first();

        $data = [
            'drawing' => $fastenerItem->drawing,
            'factor' => 1
        ];

        $from = $this->from("/items/{$detailItem->id}/confirm-component-replacement");

        $response = $from->post("/items/{$detailItem->id}/confirm-component-replacement", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_item_is_the_same()
    {
        $item = Item::whereHas('relatedSpecifications', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL));
        })->first();

        $data = [
            'drawing' => $item->drawing,
            'factor' => 1
        ];

        $from = $this->from("/items/{$item->id}/confirm-component-replacement");

        $response = $from->post("/items/{$item->id}/confirm-component-replacement", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_factor_is_not_provided()
    {
        $item = Item::whereHas('relatedSpecifications', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL));
        })->first();

        $newItem = Item::whereHas('relatedSpecifications', function ($query) use ($item) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL))
                ->where('items.id', '<>', $item->id);
        })->first();

        $data = [
            'drawing' => $newItem->drawing,
            'factor' => null
        ];

        $from = $this->from("/items/{$item->id}/confirm-component-replacement");

        $response = $from->post("/items/{$item->id}/confirm-component-replacement", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_factor_is_not_number()
    {
        $item = Item::whereHas('relatedSpecifications', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL));
        })->first();

        $newItem = Item::whereHas('relatedSpecifications', function ($query) use ($item) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL))
                ->where('items.id', '<>', $item->id);
        })->first();

        $data = [
            'drawing' => $newItem->drawing,
            'factor' => 'Не число.'
        ];

        $from = $this->from("/items/{$item->id}/confirm-component-replacement");

        $response = $from->post("/items/{$item->id}/confirm-component-replacement", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_factor_less_than_0()
    {
        $item = Item::whereHas('relatedSpecifications', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL));
        })->first();

        $newItem = Item::whereHas('relatedSpecifications', function ($query) use ($item) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL))
                ->where('items.id', '<>', $item->id);
        })->first();

        $data = [
            'drawing' => $newItem->drawing,
            'factor' => -1
        ];

        $from = $this->from("/items/{$item->id}/confirm-component-replacement");

        $response = $from->post("/items/{$item->id}/confirm-component-replacement", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_factor_greater_than_99()
    {
        $item = Item::whereHas('relatedSpecifications', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL));
        })->first();

        $newItem = Item::whereHas('relatedSpecifications', function ($query) use ($item) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL))
                ->where('items.id', '<>', $item->id);
        })->first();

        $data = [
            'drawing' => $newItem->drawing,
            'factor' => 100
        ];

        $from = $this->from("/items/{$item->id}/confirm-component-replacement");

        $response = $from->post("/items/{$item->id}/confirm-component-replacement", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_factor_for_unit_items_is_not_integer()
    {
        $item = Item::whereHas('relatedSpecifications', function ($query) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL));
        })->first();

        $newItem = Item::whereHas('relatedSpecifications', function ($query) use ($item) {
            $query->where('items.group_id', Group::getGroupIdByGroupName(Group::DETAIL))
                ->where('items.id', '<>', $item->id);
        })->first();

        $data = [
            'drawing' => $newItem->drawing,
            'factor' => 1.5
        ];

        $from = $this->from("/items/{$item->id}/confirm-component-replacement");

        $response = $from->post("/items/{$item->id}/confirm-component-replacement", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
