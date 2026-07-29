<?php

namespace Tests\Feature\Api;

use App\Models\Employee;
use App\Models\User;
use App\Services\EmployeeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $employee;

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
            'employee_id' => 'EMP-API-001',
            'first_name' => 'Api',
            'last_name' => 'Tester',
            'email' => 'api.test@example.com',
            'password' => 'password123',
            'is_active' => 1,
        ]);
    }

    public function test_api_can_fetch_paginated_employees(): void
    {
        $response = $this->getJson('/api/v1/employees');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'employee_id', 'full_name', 'email', 'department', 'designation']
                ],
                'links',
                'meta'
            ]);
    }

    public function test_api_can_create_new_employee(): void
    {
        $response = $this->postJson('/api/v1/employees', [
            'first_name' => 'ApiUser',
            'last_name' => 'New',
            'employee_id' => 'EMP-API-999',
            'username' => 'apiuser999',
            'email' => 'apiuser999@example.com',
            'password' => 'secret123',
            'salary' => 7500.00,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.employee_id', 'EMP-API-999');

        $this->assertDatabaseHas('xin_employees', ['employee_id' => 'EMP-API-999']);
    }

    public function test_api_can_fetch_employee_details(): void
    {
        $response = $this->getJson('/api/v1/employees/' . $this->employee->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $this->employee->id)
            ->assertJsonPath('data.employee_id', 'EMP-API-001');
    }

    public function test_api_can_update_employee(): void
    {
        $response = $this->putJson("/api/v1/employees/{$this->employee->user_id}", [
            'first_name' => 'UpdatedApi',
            'last_name' => 'User',
            'employee_id' => $this->employee->employee_id,
            'email' => $this->employee->email,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.first_name', 'UpdatedApi');

        $this->assertDatabaseHas('xin_employees', [
            'user_id' => $this->employee->user_id,
            'first_name' => 'UpdatedApi',
        ]);
    }

    public function test_api_can_delete_employee(): void
    {
        $service = app(EmployeeService::class);
        $targetEmployee = $service->createEmployee([
            'employee_id' => 'EMP-API-DEL',
            'first_name' => 'ApiDelete',
            'last_name' => 'Target',
            'email' => 'apidelete.me@example.com',
            'password' => 'password123',
        ]);

        $response = $this->deleteJson("/api/v1/employees/{$targetEmployee->user_id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Employee record deleted successfully.');

        $this->assertDatabaseMissing('xin_employees', ['user_id' => $targetEmployee->user_id]);
    }
}
