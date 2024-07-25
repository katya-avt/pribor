<?php

namespace Tests\Feature\Requests\Range\ManufacturingProcess\Cover\CoverItem;

use App\Models\Range\Cover;
use App\Models\Range\CoverItem;
use App\Models\Range\Group;
use App\Models\Range\Item;
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
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $data = [
            'drawing' => null,
            'area' => 150.12,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/{$coverItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$coverItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_drawing_is_already_on_list()
    {
        $coverData = [
            'number' => '10000000'
        ];

        $cover = Cover::create($coverData);

        $firstPaintItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::PAINT))->first();

        CoverItem::factory()->create([
            'cover_number' => $cover->number,
            'item_id' => $firstPaintItem->id
        ]);

        $secondPaintItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::PAINT))
            ->where('items.id', '<>', $firstPaintItem->id)
            ->first();

        CoverItem::factory()->create([
            'cover_number' => $cover->number,
            'item_id' => $secondPaintItem->id
        ]);

        $data = [
            'drawing' => $secondPaintItem->drawing,
            'area' => 150.12,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/{$firstPaintItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$firstPaintItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_not_fail_when_unique_drawing_is_not_updated()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $data = [
            'drawing' => $coverItem->drawing,
            'area' => 150.12,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/{$coverItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$coverItem->id}", $data);
        $response->assertRedirect("/covers/{$cover->number}");
    }

    /** @test */
    public function request_should_fail_when_drawing_is_not_selected_from_list()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $data = [
            'drawing' => 'Значение не из списка.',
            'area' => 150.12,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/{$coverItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$coverItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_selected_item_is_not_cover()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $detailItem = Item::where('group_id', Group::getGroupIdByGroupName(Group::DETAIL))->first();

        $data = [
            'drawing' => $detailItem->drawing,
            'area' => 150.12,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/{$coverItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$coverItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_area_is_not_provided()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $data = [
            'drawing' => $coverItem->drawing,
            'area' => null,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/{$coverItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$coverItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_area_is_not_a_number()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $data = [
            'drawing' => $coverItem->drawing,
            'area' => 'Не число.',
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/{$coverItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$coverItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_area_less_than_0()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $data = [
            'drawing' => $coverItem->drawing,
            'area' => -150.12,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/{$coverItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$coverItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_area_greater_than_99999()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $data = [
            'drawing' => $coverItem->drawing,
            'area' => 1000000,
            'consumption' => 0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/{$coverItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$coverItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_consumption_is_not_provided()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $data = [
            'drawing' => $coverItem->drawing,
            'area' => 150.12,
            'consumption' => null
        ];

        $from = $this->from("/covers/{$cover->number}/{$coverItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$coverItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_consumption_is_not_a_number()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $data = [
            'drawing' => $coverItem->drawing,
            'area' => 150.12,
            'consumption' => 'Не число.'
        ];

        $from = $this->from("/covers/{$cover->number}/{$coverItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$coverItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_consumption_less_than_0()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $data = [
            'drawing' => $coverItem->drawing,
            'area' => 150.12,
            'consumption' => -0.00005
        ];

        $from = $this->from("/covers/{$cover->number}/{$coverItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$coverItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_consumption_greater_than_9()
    {
        $cover = Cover::has('items')->whereNull('added_to_order_at')->first();
        $coverItem = $cover->items->first();

        $data = [
            'drawing' => $coverItem->drawing,
            'area' => 150.12,
            'consumption' => 100
        ];

        $from = $this->from("/covers/{$cover->number}/{$coverItem->id}/edit");

        $response = $from->patch("/covers/{$cover->number}/{$coverItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
