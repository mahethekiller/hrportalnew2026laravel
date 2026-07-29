<?php

namespace Tests\Feature\Reports;

use App\Models\EmployeeLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReportManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_employees_log')) {
            Schema::create('xin_employees_log', function ($table) {
                $table->bigIncrements('id');
                $table->integer('user_id')->default(1);
                $table->string('employee_id', 100)->nullable();
                $table->string('first_name', 100);
                $table->string('last_name', 100);
                $table->string('username', 100)->nullable();
                $table->string('email', 150)->nullable();
                $table->integer('department_id')->default(1);
                $table->integer('designation_id')->default(1);
                $table->string('created_at', 50)->nullable();
                $table->string('created_by', 100)->nullable();
                $table->string('updated_by', 100)->nullable();
                $table->string('updated_date', 50)->nullable();
                $table->text('updates')->nullable();
            });
        }

        if (!Schema::hasTable('xin_employees')) {
            Schema::create('xin_employees', function ($table) {
                $table->bigIncrements('user_id');
                $table->string('employee_id', 200)->nullable();
                $table->string('first_name', 100);
                $table->string('last_name', 100);
                $table->string('email', 100)->nullable();
                $table->integer('department_id')->default(1);
                $table->integer('designation_id')->default(1);
                $table->integer('is_active')->default(1);
                $table->string('date_of_joining', 50)->nullable();
            });
        }

        if (!Schema::hasTable('xin_make_payment')) {
            Schema::create('xin_make_payment', function ($table) {
                $table->bigIncrements('make_payment_id');
                $table->integer('employee_id');
                $table->integer('department_id')->default(1);
                $table->decimal('basic_salary', 10, 2)->default(0.00);
                $table->decimal('net_salary', 10, 2)->default(0.00);
                $table->string('payment_date', 50)->nullable();
                $table->integer('status')->default(1);
            });
        }

        if (!Schema::hasTable('xin_departments')) {
            Schema::create('xin_departments', function ($table) {
                $table->bigIncrements('department_id');
                $table->string('department_name', 200);
            });
        }

        if (!Schema::hasTable('xin_leave_applications')) {
            Schema::create('xin_leave_applications', function ($table) {
                $table->bigIncrements('leave_id');
                $table->integer('status')->default(1);
            });
        }

        if (!Schema::hasTable('xin_assets')) {
            Schema::create('xin_assets', function ($table) {
                $table->bigIncrements('asset_id');
            });
        }

        if (!Schema::hasTable('xin_training')) {
            Schema::create('xin_training', function ($table) {
                $table->bigIncrements('training_id');
            });
        }
    }

    public function test_executive_reporting_hub_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Executive HR Reporting Hub');
    }

    public function test_employee_reports_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reports.employees'));

        $response->assertStatus(200);
        $response->assertSee('Employee Demographics & Headcount Report', false);
    }

    public function test_payroll_reports_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reports.payroll'));

        $response->assertStatus(200);
        $response->assertSee('Payroll & Compensation Disbursements Report', false);
    }

    public function test_audit_logs_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        EmployeeLog::create([
            'first_name' => 'Vikram',
            'last_name' => 'Sharma',
            'email' => 'vikram@company.com',
            'updates' => 'Promoted to Senior Lead Developer',
        ]);

        $response = $this->actingAs($user)->get(route('reports.audit_logs'));

        $response->assertStatus(200);
        $response->assertSee('System Audit Trail Logs');
        $response->assertSee('Promoted to Senior Lead Developer');
    }

    public function test_api_v1_can_fetch_executive_summary_report(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/v1/reports/summary');

        $response->assertStatus(200)
                 ->assertJsonPath('message', 'Executive HR portal statistics fetched successfully.');
    }
}
