<?php

namespace Tests\Feature\Organization;

use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $service = app(EmployeeService::class);
        $this->employee = $service->createEmployee([
            'employee_id' => 'EMP-DEPT-001',
            'first_name' => 'Dept',
            'last_name' => 'Tester',
            'email' => 'dept.test@example.com',
            'password' => 'password123',
        ]);
    }

    public function test_department_directory_index_can_be_rendered(): void
    {
        $response = $this->actingAs($this->employee)->get(route('departments.index'));

        $response->assertStatus(200);
        $response->assertSee('Departments');
    }

    public function test_new_department_can_be_created(): void
    {
        $response = $this->actingAs($this->employee)
            ->post(route('departments.store'), [
                'department_name' => 'Artificial Intelligence Engineering',
                'company_id' => 1,
            ]);

        $response->assertRedirect(route('departments.index'));
        $this->assertDatabaseHas('xin_departments', [
            'department_name' => 'Artificial Intelligence Engineering',
        ]);
    }
}
