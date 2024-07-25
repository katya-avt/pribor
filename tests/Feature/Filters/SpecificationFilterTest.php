<?php

namespace Tests\Feature\Filters;

use App\Http\Filters\SpecificationFilter;
use App\Models\Range\Item;
use App\Models\Range\Specification;
use App\Models\Range\SpecificationItem;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SpecificationFilterTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function filterProvider()
    {
        return [
            'specifications_can_be_filtered_by_number' => [
                'data' => [
                    'search' => '1234',
                    'drawing' => null
                ],
                'includedSpecification' => '00001234',
                'excludedSpecification' => '00005678'
            ],

            'specifications_can_be_filtered_by_contained_item' => [
                'data' => [
                    'search' => null,
                    'drawing' => 'Деталь'
                ],
                'includedSpecification' => '00005678',
                'excludedSpecification' => '00001234'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider filterProvider
     * @param array $mockedFilterData
     * @param string $includedSpecification
     * @param string $excludedSpecification
     */
    public function filter_results_as_expected($mockedFilterData, $includedSpecification, $excludedSpecification)
    {
        $firstSpecification = Specification::create([
            'number' => '00001234'
        ]);

        $secondSpecification = Specification::create([
            'number' => '00005678'
        ]);

        $detailItem = Item::factory()->create([
            'drawing' => 'Деталь',
            'name' => 'Деталь',
            'item_type_id' => 'Собственный',
            'group_id' => 'Детали',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Склад готовой продукции',
            'manufacture_type_id' => 'Под заказ'
        ]);

        SpecificationItem::factory()->create([
            'specification_number' => $secondSpecification->number,
            'item_id' => $detailItem->id
        ]);

        $filter = app()->make(SpecificationFilter::class, ['queryParams' => array_filter($mockedFilterData)]);

        $specifications = Specification::with('items')->filter($filter)->get();

        $includedSpecification = Specification::find($includedSpecification);
        $excludedSpecification = Specification::find($excludedSpecification);

        $this->assertTrue($specifications->contains($includedSpecification));
        $this->assertFalse($specifications->contains($excludedSpecification));
    }
}
