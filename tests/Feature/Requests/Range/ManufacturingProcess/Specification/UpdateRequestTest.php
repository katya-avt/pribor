<?php

namespace Tests\Feature\Requests\Range\ManufacturingProcess\Specification;

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
        $specification = Specification::whereNull('added_to_order_at')->first();

        $from = $this->from("/specifications/{$specification->number}/edit");

        $response = $from->patch("/specifications/{$specification->number}", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect('/specifications');
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }

    /** @test */
    public function request_should_fail_when_specification_number_is_not_unique_in_specifications()
    {
        $firstSpecification = Specification::whereNull('added_to_order_at')->first();

        $secondSpecification = Specification::whereNull('added_to_order_at')
            ->where('number', '<>', $firstSpecification->number)
            ->first();

        $secondSpecificationData = [
            'number' => $secondSpecification->number
        ];

        $from = $this->from("/specifications/{$firstSpecification->number}/edit");

        $response = $from->patch("/specifications/{$firstSpecification->number}", $secondSpecificationData);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_not_fail_when_unique_specification_number_is_not_updated()
    {
        $specification = Specification::whereNull('added_to_order_at')->first();

        $specificationData = [
            'number' => $specification->number
        ];

        $response = $this->patch("/specifications/{$specification->number}", $specificationData);
        $response->assertRedirect("/specifications");
    }

    /** @test */
    public function request_should_fail_when_specification_number_is_not_unique_in_covers()
    {
        $cover = Cover::first();

        $specification = Specification::whereNull('added_to_order_at')->first();

        $data = [
            'number' => $cover->number
        ];

        $from = $this->from("/specifications/{$specification->number}/edit");

        $response = $from->patch("/specifications/{$specification->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_specification_number_is_not_unique_in_routes()
    {
        $route = Route::first();

        $specification = Specification::whereNull('added_to_order_at')->first();

        $data = [
            'number' => $route->number
        ];

        $from = $this->from("/specifications/{$specification->number}/edit");

        $response = $from->patch("/specifications/{$specification->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
