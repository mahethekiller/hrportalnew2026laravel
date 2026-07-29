<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use App\Services\EmployeeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EmployeeManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $employee;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_office_shift')) {
            Schema::create('xin_office_shift', function ($table) {
                $table->bigIncrements('office_shift_id');
                $table->string('shift_name', 150)->default('Default Shift');
            });
        }

        $service = app(EmployeeService::class);
        $this->employee = $service->createEmployee([
            'employee_id' => 'EMP-TEST-001',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin.test@example.com',
            'password' => 'password123',
            'is_active' => 1,
        ]);
        $this->user = $this->employee->user;
    }

    public function test_employee_directory_index_can_be_rendered(): void
    {
        $response = $this->actingAs($this->employee)->get(route('employees.index'));

        $response->assertStatus(200);
        $response->assertSee('Employee Directory');
    }

    public function test_create_employee_screen_can_be_rendered(): void
    {
        $response = $this->actingAs($this->employee)->get(route('employees.create'));

        $response->assertStatus(200);
        $response->assertSee('Add New Employee');
    }

    public function test_new_employee_can_be_created(): void
    {
        $response = $this->actingAs($this->employee)->post(route('employees.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'employee_id' => 'EMP-NEW-999',
            'username' => 'johndoe999',
            'email' => 'john.doe.new@example.com',
            'password' => 'password123',
            'salary' => 5000.00,
        ]);

        $response->assertRedirect(route('employees.index'));
        $this->assertDatabaseHas('xin_employees', [
            'employee_id' => 'EMP-NEW-999',
            'email' => 'john.doe.new@example.com',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe.new@example.com',
        ]);
    }

    public function test_employee_profile_screen_can_be_rendered(): void
    {
        $response = $this->actingAs($this->employee)->get(route('employees.show', $this->employee->user_id));

        $response->assertStatus(200);
        $response->assertSee($this->employee->first_name);
    }

    public function test_employee_can_be_updated(): void
    {
        $response = $this->actingAs($this->employee)->put(route('employees.update', $this->employee->user_id), [
            'first_name' => 'UpdatedAdmin',
            'last_name' => 'UpdatedUser',
            'employee_id' => $this->employee->employee_id,
            'card_no' => 'CARD-9988',
            'email' => $this->employee->email,
        ]);

        $response->assertRedirect(route('employees.index'));
        $this->assertDatabaseHas('xin_employees', [
            'user_id' => $this->employee->user_id,
            'first_name' => 'UpdatedAdmin',
            'card_no' => 'CARD-9988',
        ]);
    }

    public function test_employee_record_can_be_deleted(): void
    {
        $service = app(EmployeeService::class);
        $targetEmployee = $service->createEmployee([
            'employee_id' => 'EMP-DEL-101',
            'first_name' => 'Delete',
            'last_name' => 'Target',
            'email' => 'delete.me@example.com',
            'password' => 'password123',
        ]);

        $response = $this->actingAs($this->employee)->delete(route('employees.destroy', $targetEmployee->user_id));

        $response->assertRedirect(route('employees.index'));
        $this->assertDatabaseMissing('xin_employees', [
            'user_id' => $targetEmployee->user_id,
        ]);
    }
}
