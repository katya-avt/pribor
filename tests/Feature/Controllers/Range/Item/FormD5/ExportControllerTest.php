<?php

namespace Tests\Feature\Controllers\Range\Item\FormD5;

use App\Models\Range\Item;
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
    public function formD5_can_be_exported_to_excel_using_view()
    {
        $item = Item::has('relatedSpecifications')->first();

        Excel::fake();

        $response = $this->get("/items/{$item->id}/form-d5/export");
        $response->assertOk();

        Excel::assertDownloaded("formD5-{$item->id}.xlsx");
    }
}
