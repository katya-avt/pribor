<?php

namespace Tests\Feature\Requests\Range\ManufacturingProcess\Cover\CoverItem;

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
    public function request_should_fail_when_drawing_is_not_provided()
    {
        $cover = Cover::whereNull('added_to_order_at')->first();

        $data = [
            'drawing' => null,
            'area' => 150.12,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/create");

        $response = $from->post("/covers/{$cover->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_drawing_is_already_on_list()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $data = [
            'drawing' => $coverItem->drawing,
            'area' => 150.12,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/create");

        $response = $from->post("/covers/{$cover->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_drawing_is_not_selected_from_list()
    {
        $cover = Cover::whereNull('added_to_order_at')->first();

        $data = [
            'drawing' => 'Значение не из списка.',
            'area' => 150.12,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/create");

        $response = $from->post("/covers/{$cover->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_item_is_not_cover()
    {
        $cover = Cover::whereNull('added_to_order_at')->first();

        $detailItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))->first();

        $data = [
            'drawing' => $detailItem->drawing,
            'area' => 150.12,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/create");

        $response = $from->post("/covers/{$cover->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_area_is_not_provided()
    {
        $coverData = [
            'number' => '10000000'
        ];

        $cover = Cover::create($coverData);

        $paintItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::PAINT))->first();

        $data = [
            'drawing' => $paintItem->drawing,
            'area' => null,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/create");

        $response = $from->post("/covers/{$cover->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_area_is_not_a_number()
    {
        $coverData = [
            'number' => '10000000'
        ];

        $cover = Cover::create($coverData);

        $paintItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::PAINT))->first();

        $data = [
            'drawing' => $paintItem->drawing,
            'area' => 'Не число.',
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/create");

        $response = $from->post("/covers/{$cover->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_area_less_than_0()
    {
        $coverData = [
            'number' => '10000000'
        ];

        $cover = Cover::create($coverData);

        $paintItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::PAINT))->first();

        $data = [
            'drawing' => $paintItem->drawing,
            'area' => -150.12,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/create");

        $response = $from->post("/covers/{$cover->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_area_greater_than_99999()
    {
        $coverData = [
            'number' => '10000000'
        ];

        $cover = Cover::create($coverData);

        $paintItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::PAINT))->first();

        $data = [
            'drawing' => $paintItem->drawing,
            'area' => 1000000,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/create");

        $response = $from->post("/covers/{$cover->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_consumption_is_not_provided()
    {
        $coverData = [
            'number' => '10000000'
        ];

        $cover = Cover::create($coverData);

        $paintItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::PAINT))->first();

        $data = [
            'drawing' => $paintItem->drawing,
            'area' => 150.12,
            'consumption' => null
        ];

        $from = $this->from("/covers/{$cover->number}/create");

        $response = $from->post("/covers/{$cover->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_consumption_is_not_a_number()
    {
        $coverData = [
            'number' => '10000000'
        ];

        $cover = Cover::create($coverData);

        $paintItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::PAINT))->first();

        $data = [
            'drawing' => $paintItem->drawing,
            'area' => 150.12,
            'consumption' => 'Не число.'
        ];

        $from = $this->from("/covers/{$cover->number}/create");

        $response = $from->post("/covers/{$cover->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_consumption_less_than_0()
    {
        $coverData = [
            'number' => '10000000'
        ];

        $cover = Cover::create($coverData);

        $paintItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::PAINT))->first();

        $data = [
            'drawing' => $paintItem->drawing,
            'area' => 150.12,
            'consumption' => -0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/create");

        $response = $from->post("/covers/{$cover->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_consumption_greater_than_9()
    {
        $coverData = [
            'number' => '10000000'
        ];

        $cover = Cover::create($coverData);

        $paintItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::PAINT))->first();

        $data = [
            'drawing' => $paintItem->drawing,
            'area' => 150.12,
            'consumption' => 100
        ];

        $from = $this->from("/covers/{$cover->number}/create");

        $response = $from->post("/covers/{$cover->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
