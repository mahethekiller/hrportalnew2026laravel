<?php

namespace Tests\Feature\Asset;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AssetManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_assets')) {
            Schema::create('xin_assets', function ($table) {
                $table->bigIncrements('assets_id');
                $table->integer('assets_category_id')->default(1);
                $table->integer('company_id')->default(1);
                $table->integer('employee_id')->nullable();
                $table->string('company_asset_code', 100)->nullable();
                $table->string('name', 255);
                $table->string('purchase_date', 50)->nullable();
                $table->string('invoice_number', 100)->nullable();
                $table->string('manufacturer', 150)->nullable();
                $table->string('serial_number', 150)->nullable();
                $table->string('warranty_end_date', 50)->nullable();
                $table->text('asset_note')->nullable();
                $table->string('asset_image', 255)->nullable();
                $table->integer('is_working')->default(1);
                $table->string('created_at', 50)->nullable();
            });
        }

        if (!Schema::hasTable('xin_employees')) {
            Schema::create('xin_employees', function ($table) {
                $table->increments('user_id');
                $table->string('employee_id', 200)->nullable();
                $table->string('first_name', 100);
                $table->string('last_name', 100);
                $table->string('email', 100)->nullable();
                $table->integer('department_id')->default(1);
                $table->integer('designation_id')->default(1);
                $table->integer('company_id')->default(1);
                $table->integer('is_active')->default(1);
            });
        }

        if (!Schema::hasTable('xin_office_shift')) {
            Schema::create('xin_office_shift', function ($table) {
                $table->bigIncrements('office_shift_id');
                $table->string('shift_name', 150)->default('Default Shift');
            });
        }
    }

    public function test_assets_dashboard_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('assets.index'));

        $response->assertStatus(200);
        $response->assertSee('Assets & Inventory');
    }

    public function test_new_asset_can_be_registered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('assets.store'), [
            'name' => 'MacBook Pro 16 M2',
            'company_asset_code' => 'AST-2026-888',
            'serial_number' => 'C02FX9999',
            'manufacturer' => 'Apple',
            'employee_id' => 1,
            'is_working' => 1,
        ]);

        $response->assertRedirect(route('assets.index'));
        $this->assertDatabaseHas('xin_assets', [
            'name' => 'MacBook Pro 16 M2',
            'company_asset_code' => 'AST-2026-888',
            'employee_id' => 1,
        ]);
    }

    public function test_api_v1_can_fetch_and_create_assets(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.v1.assets.store'), [
            'name' => 'Dell UltraSharp 27 Monitor',
            'serial_number' => 'CN-099238',
            'manufacturer' => 'Dell',
            'is_working' => 1,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('message', 'Asset registered successfully.');

        $getResponse = $this->actingAs($user)->getJson(route('api.v1.assets.index'));
        $getResponse->assertStatus(200);
    }
}
