<?php

namespace Tests\Feature\SubResources;

use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EmployeeSubResourceTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $service = app(EmployeeService::class);
        $this->employee = $service->createEmployee([
            'employee_id' => 'EMP-SUB-001',
            'first_name' => 'Sub',
            'last_name' => 'ResourceUser',
            'email' => 'subresource@example.com',
            'password' => 'password123',
        ]);
    }

    public function test_can_upload_employee_document(): void
    {
        $file = UploadedFile::fake()->create('passport.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->employee)
            ->post(route('employee-documents.store'), [
                'employee_id' => $this->employee->user_id,
                'title' => 'Passport Scan',
                'date_of_expiry' => '2030-12-31',
                'notification_email' => 'alert@example.com',
                'document_file' => $file,
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('xin_employee_documents', [
            'employee_id' => $this->employee->user_id,
            'title' => 'Passport Scan',
        ]);
    }

    public function test_can_add_emergency_contact(): void
    {
        $response = $this->actingAs($this->employee)
            ->post(route('employee-contacts.store'), [
                'employee_id' => $this->employee->user_id,
                'contact_name' => 'Jane Doe',
                'relation' => 'Spouse',
                'work_phone' => '1234567890',
                'mobile_phone' => '1234567890',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('xin_employee_contacts', [
            'employee_id' => $this->employee->user_id,
            'contact_name' => 'Jane Doe',
            'relation' => 'Spouse',
        ]);
    }

    public function test_can_add_bank_account(): void
    {
        $response = $this->actingAs($this->employee)
            ->post(route('employee-bankaccounts.store'), [
                'employee_id' => $this->employee->user_id,
                'account_title' => 'Main Checking',
                'account_number' => 'ACC123456789',
                'bank_name' => 'Antigravity National Bank',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('xin_employee_bankaccount', [
            'employee_id' => $this->employee->user_id,
            'account_title' => 'Main Checking',
        ]);
    }

    public function test_can_add_qualification(): void
    {
        $response = $this->actingAs($this->employee)
            ->post(route('employee-qualifications.store'), [
                'employee_id' => $this->employee->user_id,
                'name' => 'B.S. Computer Science',
                'institute_name' => 'MIT',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('xin_employee_qualification', [
            'employee_id' => $this->employee->user_id,
            'name' => 'B.S. Computer Science',
        ]);
    }

    public function test_can_add_work_experience(): void
    {
        $response = $this->actingAs($this->employee)
            ->post(route('employee-experiences.store'), [
                'employee_id' => $this->employee->user_id,
                'company_name' => 'Acme Tech',
                'post' => 'Senior Developer',
                'from_date' => '2021-01-01',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('xin_employee_work_experience', [
            'employee_id' => $this->employee->user_id,
            'company_name' => 'Acme Tech',
        ]);
    }
}
