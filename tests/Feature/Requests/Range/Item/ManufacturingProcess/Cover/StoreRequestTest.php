<?php

namespace Tests\Feature\Requests\Range\Item\ManufacturingProcess\Cover;

use App\Models\Range\Cover;
use App\Models\Range\Group;
use App\Models\Range\Item;
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
    public function request_should_fail_when_cover_number_is_not_provided()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $data = [
            'number' => null
        ];

        $from = $this->from("/items/{$item->id}/covers/create");

        $response = $from->post("/items/{$item->id}/covers", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_cover_number_is_not_selected_from_list()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $data = [
            'number' => 'Значение не из списка.'
        ];

        $from = $this->from("/items/{$item->id}/covers/create");

        $response = $from->post("/items/{$item->id}/covers", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_cover_number_is_already_on_list()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $data = [
            'number' => $item->cover_number
        ];

        $from = $this->from("/items/{$item->id}/covers/create");

        $response = $from->post("/items/{$item->id}/covers", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_cover_number_is_empty()
    {
        $item = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))
            ->whereNotNull('specification_number')->whereNotNull('cover_number')->whereNotNull('route_number')
            ->first();

        $cover = Cover::find($item->cover_number);
        $cover->items()->detach();

        $data = [
            'number' => $item->cover_number
        ];

        $from = $this->from("/items/{$item->id}/covers/create");

        $response = $from->post("/items/{$item->id}/covers", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
