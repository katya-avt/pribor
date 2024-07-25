<?php

namespace Tests\Feature\Requests\Range\ManufacturingProcess\Cover;

use App\Models\Range\Cover;
use App\Models\Range\Route;
use App\Models\Range\Specification;
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

    public function validationProvider()
    {
        return (new StoreRequestTest())->validationProvider();
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function validation_results_as_expected($shouldPass, $mockedRequestData)
    {
        $cover = Cover::whereNull('added_to_order_at')->first();

        $from = $this->from("/covers/{$cover->number}/edit");

        $response = $from->patch("/covers/{$cover->number}", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect('/covers');
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }

    /** @test */
    public function request_should_fail_when_cover_number_is_not_unique_in_specifications()
    {
        $specification = Specification::first();

        $cover = Cover::whereNull('added_to_order_at')->first();

        $coverData = [
            'number' => $specification->number
        ];

        $from = $this->from("/covers/{$cover->number}/edit");

        $response = $from->patch("/covers/{$cover->number}", $coverData);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_cover_number_is_not_unique_in_covers()
    {
        $firstCover = Cover::whereNull('added_to_order_at')->first();

        $secondCover = Cover::whereNull('added_to_order_at')
            ->where('number', '<>', $firstCover->number)
            ->first();

        $secondCoverData = [
            'number' => $secondCover->number
        ];

        $from = $this->from("/covers/{$firstCover->number}/edit");

        $response = $from->patch("/covers/{$firstCover->number}", $secondCoverData);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_not_fail_when_unique_cover_number_is_not_updated()
    {
        $cover = Cover::whereNull('added_to_order_at')->first();

        $coverData = [
            'number' => $cover->number
        ];

        $from = $this->from("/covers/{$cover->number}/edit");

        $response = $from->patch("/covers/{$cover->number}", $coverData);
        $response->assertRedirect('/covers');
    }

    /** @test */
    public function request_should_fail_when_cover_number_is_not_unique_in_routes()
    {
        $route = Route::first();

        $cover = Cover::whereNull('added_to_order_at')->first();

        $coverData = [
            'number' => $route->number
        ];

        $from = $this->from("/covers/{$cover->number}/edit");

        $response = $from->patch("/covers/{$cover->number}", $coverData);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
