<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Route;

use App\Models\Range\Route;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExportControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function route_can_be_exported_to_excel_using_view()
    {
        $route = Route::has('points')->first();

        Excel::fake();

        $response = $this->get("/routes/{$route->number}/export");
        $response->assertOk();

        Excel::assertDownloaded("route-{$route->number}.xlsx");
    }
}
