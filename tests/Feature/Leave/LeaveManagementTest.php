<?php

namespace Tests\Feature\Leave;

use App\Models\Employee;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LeaveManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_leave_type')) {
            Schema::create('xin_leave_type', function ($table) {
                $table->bigIncrements('leave_type_id');
                $table->integer('company_id')->default(1);
                $table->string('type_name', 150);
                $table->integer('days_per_year')->default(12);
                $table->integer('status')->default(1);
                $table->string('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('xin_leave_applications')) {
            Schema::create('xin_leave_applications', function ($table) {
                $table->bigIncrements('leave_id');
                $table->integer('company_id')->default(1);
                $table->integer('employee_id');
                $table->integer('manager_id')->nullable();
                $table->integer('leave_type_id');
                $table->string('start_duration', 50)->nullable();
                $table->string('from_date', 50);
                $table->string('to_date', 50);
                $table->string('end_duration', 50)->nullable();
                $table->string('applied_on', 50)->nullable();
                $table->integer('casual_deducted')->default(0);
                $table->integer('earned_deducted')->default(0);
                $table->text('reason')->nullable();
                $table->text('remarks')->nullable();
                $table->integer('status')->default(1);
                $table->string('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('xin_employees')) {
            Schema::create('xin_employees', function ($table) {
                $table->increments('user_id');
                $table->string('employee_id', 200)->nullable();
                $table->string('first_name', 100);
                $table->string('last_name', 100);
                $table->string('email', 100)->nullable();
                $table->string('username', 100)->nullable();
                $table->string('password', 200)->nullable();
                $table->integer('department_id')->default(1);
                $table->integer('designation_id')->default(1);
                $table->integer('company_id')->default(1);
                $table->integer('is_active')->default(1);
                $table->string('created_at')->nullable();
            });
        }
    }

    public function test_leave_applications_directory_index_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('leaves.index'));

        $response->assertStatus(200);
        $response->assertSee('Leave Management');
    }

    public function test_leave_types_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('leave-types.index'));

        $response->assertStatus(200);
        $response->assertSee('Leave Types & Quotas');
    }

    public function test_new_leave_type_can_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('leave-types.store'), [
            'type_name' => 'Earned Leave',
            'days_per_year' => 15,
        ]);

        $response->assertRedirect(route('leave-types.index'));
        $this->assertDatabaseHas('xin_leave_type', [
            'type_name' => 'Earned Leave',
            'days_per_year' => 15,
        ]);
    }

    public function test_employee_can_submit_leave_application(): void
    {
        $user = User::factory()->create();

        $employeeService = app(\App\Services\EmployeeService::class);
        $employee = $employeeService->createEmployee([
            'employee_id' => 'EMP-101',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);

        $leaveType = LeaveType::create([
            'type_name' => 'Casual Leave',
            'days_per_year' => 12,
        ]);

        $response = $this->actingAs($user)->post(route('leaves.store'), [
            'employee_id' => $employee->user_id,
            'leave_type_id' => $leaveType->leave_type_id,
            'from_date' => '2026-08-01',
            'to_date' => '2026-08-03',
            'reason' => 'Family Event',
        ]);

        $response->assertRedirect(route('leaves.index'));
        $this->assertDatabaseHas('xin_leave_applications', [
            'employee_id' => $employee->user_id,
            'leave_type_id' => $leaveType->leave_type_id,
            'reason' => 'Family Event',
            'status' => 1,
        ]);
    }

    public function test_manager_can_approve_leave_application(): void
    {
        $user = User::factory()->create();

        $employeeService = app(\App\Services\EmployeeService::class);
        $employee = $employeeService->createEmployee([
            'employee_id' => 'EMP-102',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'password' => 'password123',
        ]);

        $leaveType = LeaveType::create([
            'type_name' => 'Sick Leave',
            'days_per_year' => 10,
        ]);

        $leave = LeaveApplication::create([
            'employee_id' => $employee->user_id,
            'leave_type_id' => $leaveType->leave_type_id,
            'from_date' => '2026-08-05',
            'to_date' => '2026-08-06',
            'reason' => 'Medical Checkup',
            'status' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('leaves.update-status', $leave->leave_id), [
            'status' => 2,
            'remarks' => 'Approved by manager',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('xin_leave_applications', [
            'leave_id' => $leave->leave_id,
            'status' => 2,
            'remarks' => 'Approved by manager',
        ]);
    }

    public function test_api_v1_can_fetch_and_submit_leaves(): void
    {
        $employeeService = app(\App\Services\EmployeeService::class);
        $employee = $employeeService->createEmployee([
            'employee_id' => 'EMP-103',
            'first_name' => 'API',
            'last_name' => 'Tester',
            'email' => 'api.tester@example.com',
            'password' => 'password123',
        ]);

        $leaveType = LeaveType::create([
            'type_name' => 'API Leave',
            'days_per_year' => 10,
        ]);

        $response = $this->postJson(route('api.v1.leaves.store'), [
            'employee_id' => $employee->user_id,
            'leave_type_id' => $leaveType->leave_type_id,
            'from_date' => '2026-09-01',
            'to_date' => '2026-09-02',
            'reason' => 'API Submission',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('message', 'Leave application submitted successfully.');

        $getResponse = $this->getJson(route('api.v1.leaves.index'));
        $getResponse->assertStatus(200);
    }
}
