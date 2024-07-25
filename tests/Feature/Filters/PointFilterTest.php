<?php

namespace Tests\Feature\Filters;

use App\Http\Filters\PointFilter;
use App\Models\Range\Point;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PointFilterTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function filterProvider()
    {
        return [
            'points_can_be_filtered_by_code_or_name' => [
                'data' => [
                    'search' => 'Лаз'
                ],
                'includedPoint' => 'ЛазГрав',
                'excludedPoint' => '1.1'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider filterProvider
     * @param array $mockedFilterData
     * @param string $includedPoint
     * @param string $excludedPoint
     */
    public function filter_results_as_expected($mockedFilterData, $includedPoint, $excludedPoint)
    {
        $filter = app()->make(PointFilter::class, ['queryParams' => array_filter($mockedFilterData)]);

        $points = Point::filter($filter)->get();

        $includedPoint = Point::where('code', $includedPoint)->first();
        $excludedPoint = Point::where('code', $excludedPoint)->first();

        $this->assertTrue($points->contains($includedPoint));
        $this->assertFalse($points->contains($excludedPoint));
    }
}
