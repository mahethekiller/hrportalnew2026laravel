<?php

namespace Tests\Feature\Payroll;

use App\Models\PayrollPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PayrollManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_make_payment')) {
            Schema::create('xin_make_payment', function ($table) {
                $table->bigIncrements('make_payment_id');
                $table->integer('employee_id');
                $table->integer('department_id')->default(1);
                $table->integer('company_id')->default(1);
                $table->integer('location_id')->default(1);
                $table->integer('designation_id')->default(1);
                $table->string('payment_date', 50);
                $table->decimal('basic_salary', 15, 2)->default(0);
                $table->decimal('payment_amount', 15, 2)->default(0);
                $table->decimal('gross_salary', 15, 2)->default(0);
                $table->decimal('total_allowances', 15, 2)->default(0);
                $table->decimal('total_deductions', 15, 2)->default(0);
                $table->decimal('net_salary', 15, 2)->default(0);
                $table->decimal('house_rent_allowance', 15, 2)->default(0);
                $table->decimal('medical_allowance', 15, 2)->default(0);
                $table->decimal('travelling_allowance', 15, 2)->default(0);
                $table->decimal('dearness_allowance', 15, 2)->default(0);
                $table->decimal('provident_fund', 15, 2)->default(0);
                $table->decimal('tax_deduction', 15, 2)->default(0);
                $table->decimal('security_deposit', 15, 2)->default(0);
                $table->decimal('advance_salary_amount', 15, 2)->default(0);
                $table->string('payment_method', 100)->default('Direct Deposit');
                $table->text('comments')->nullable();
                $table->integer('status')->default(1);
                $table->string('created_at', 50)->nullable();
            });
        }

        if (!Schema::hasTable('xin_employee_salary')) {
            Schema::create('xin_employee_salary', function ($table) {
                $table->bigIncrements('id');
                $table->integer('employee_id');
                $table->decimal('old_salary', 15, 2)->default(0);
                $table->decimal('new_salary', 15, 2)->default(0);
                $table->string('appraisal_date', 50)->nullable();
                $table->string('added_by', 100)->nullable();
                $table->string('added_date', 50)->nullable();
                $table->integer('show_status')->default(1);
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

    public function test_payroll_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('payroll.index'));

        $response->assertStatus(200);
        $response->assertSee('Payroll');
    }

    public function test_salary_history_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('salary-history.index'));

        $response->assertStatus(200);
        $response->assertSee('Salary Increment');
    }

    public function test_payroll_payment_can_be_processed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('payroll.store'), [
            'employee_id' => 1,
            'payment_date' => '2026-08-01',
            'basic_salary' => 5000,
            'house_rent_allowance' => 1000,
            'medical_allowance' => 500,
            'provident_fund' => 200,
            'tax_deduction' => 300,
            'payment_method' => 'Direct Deposit',
        ]);

        $response->assertRedirect(route('payroll.index'));
        $this->assertDatabaseHas('xin_make_payment', [
            'employee_id' => 1,
            'basic_salary' => 5000,
            'net_salary' => 6000,
        ]);
    }

    public function test_printable_payslip_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $payment = PayrollPayment::create([
            'employee_id' => 1,
            'payment_date' => '2026-08-01',
            'basic_salary' => 5000,
            'net_salary' => 5000,
            'payment_method' => 'Direct Deposit',
            'status' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('payroll.payslip', $payment->make_payment_id));

        $response->assertStatus(200);
        $response->assertSee('Salary Payslip');
    }

    public function test_api_v1_can_fetch_and_process_payroll(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.v1.payroll.store'), [
            'employee_id' => 1,
            'payment_date' => '2026-08-01',
            'basic_salary' => 7000,
            'payment_method' => 'Direct Deposit',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('message', 'Payroll payment processed successfully.');

        $getResponse = $this->actingAs($user)->getJson(route('api.v1.payroll.index'));
        $getResponse->assertStatus(200);
    }
}
