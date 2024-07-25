<?php

namespace Tests\Feature\Filters;

use App\Http\Filters\CoverFilter;
use App\Models\Range\Cover;
use App\Models\Range\CoverItem;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CoverFilterTest extends TestCase
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
            'covers_can_be_filtered_by_number' => [
                'data' => [
                    'search' => '1234',
                    'drawing' => null
                ],
                'includedCover' => '00001234',
                'excludedCover' => '00005678'
            ],

            'covers_can_be_filtered_by_contained_item' => [
                'data' => [
                    'search' => null,
                    'drawing' => 'Лакокрасочное покрытие'
                ],
                'includedCover' => '00005678',
                'excludedCover' => '00001234'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider filterProvider
     * @param array $mockedFilterData
     * @param string $includedCover
     * @param string $excludedCover
     */
    public function filter_results_as_expected($mockedFilterData, $includedCover, $excludedCover)
    {
        $firstCover = Cover::create([
            'number' => '00001234'
        ]);

        $secondCover = Cover::create([
            'number' => '00005678'
        ]);

        $paintItem = Item::factory()->create([
            'drawing' => 'Лакокрасочное покрытие',
            'name' => 'Лакокрасочное покрытие',
            'item_type_id' => 'Покупной',
            'group_id' => 'Лакокрасочные покрытия',
            'unit_code' => 'л',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас'
        ]);

        CoverItem::factory()->create([
            'cover_number' => $secondCover->number,
            'item_id' => $paintItem->id
        ]);

        $filter = app()->make(CoverFilter::class, ['queryParams' => array_filter($mockedFilterData)]);

        $covers = Cover::with('items')->filter($filter)->get();

        $includedCover = Cover::find($includedCover);
        $excludedCover = Cover::find($excludedCover);

        $this->assertTrue($covers->contains($includedCover));
        $this->assertFalse($covers->contains($excludedCover));
    }
}
