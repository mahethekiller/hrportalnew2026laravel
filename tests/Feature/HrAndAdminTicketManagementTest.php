<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\HrTicket;
use App\Models\AdminTicket;
use App\Models\Department;
use App\Models\UserRole;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HrAndAdminTicketManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $employee;
    protected Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'NavigationMenuSeeder']);

        $this->company = Company::create([
            'type_id' => 1,
            'name' => 'Antigravity Corp',
            'trading_name' => 'Antigravity',
            'username' => 'antigravity',
            'password' => bcrypt('password'),
            'registration_no' => '12345',
            'government_tax' => '12345',
            'email' => 'admin@antigravity.com',
            'logo' => 'logo.png',
            'contact_number' => '1234567890',
            'website_url' => 'antigravity.com',
            'address_1' => '123 Main St',
            'address_2' => '',
            'city' => 'Anytown',
            'state' => 'State',
            'zipcode' => '12345',
            'country' => 1,
            'is_active' => 1,
            'added_by' => 1,
        ]);

        $dept = Department::create([
            'department_id' => 1,
            'department_name' => 'IT Support',
            'company_id' => $this->company->company_id,
            'location_id' => 1,
            'employee_id' => 1,
            'added_by' => 1,
            'status' => 'active',
        ]);

        $user = \App\Models\User::factory()->create();
        $this->employee = Employee::factory()->create([
            'user_id' => $user->id,
            'company_id' => $this->company->company_id,
            'department_id' => $dept->department_id,
        ]);
    }

    public function test_hr_tickets_can_be_managed(): void
    {
        // 1. Render listing
        $response = $this->actingAs($this->employee)->get(route('hr-tickets.index'));
        $response->assertStatus(200);

        // 2. Create HR ticket
        $response = $this->actingAs($this->employee)->post(route('hr-tickets.store'), [
            'subject' => 'HR Compensation Inquiry',
            'company_id' => $this->company->company_id,
            'ticket_priority' => 'medium',
            'description' => 'I would like to request my latest salary increment slip.',
        ]);
        $response->assertRedirect(route('hr-tickets.index'));
        $this->assertDatabaseHas('xin_hr_tickets', [
            'subject' => 'HR Compensation Inquiry',
            'ticket_priority' => 'medium',
        ]);

        $ticket = HrTicket::first();

        // 3. View detail
        $response = $this->actingAs($this->employee)->get(route('hr-tickets.show', $ticket->ticket_id));
        $response->assertStatus(200);
        $response->assertSee('HR Compensation Inquiry');

        // 4. Update status by Admin
        $role = UserRole::create([
            'company_id' => 1,
            'role_name' => 'HR Admin Officer',
            'role_access' => 'custom',
            'role_resources' => 'view.hr_tickets,edit.hr_tickets',
        ]);

        $adminUserObj = \App\Models\User::factory()->create();
        $adminUser = Employee::factory()->create([
            'user_id' => $adminUserObj->id,
            'user_role_id' => $role->id,
            'company_id' => $this->company->company_id,
        ]);

        $response = $this->actingAs($adminUser)->post(route('hr-tickets.status', $ticket->ticket_id), [
            'ticket_status' => '2',
            'remarks' => 'Salary increment slip sent via email.',
        ]);

        $response->assertRedirect(route('hr-tickets.show', $ticket->ticket_id));
        $this->assertDatabaseHas('xin_hr_tickets', [
            'ticket_id' => $ticket->ticket_id,
            'ticket_status' => '2',
            'remarks' => 'Salary increment slip sent via email.',
        ]);
    }

    public function test_admin_tickets_can_be_managed(): void
    {
        // 1. Render listing
        $response = $this->actingAs($this->employee)->get(route('admin-tickets.index'));
        $response->assertStatus(200);

        // 2. Create Admin ticket
        $response = $this->actingAs($this->employee)->post(route('admin-tickets.store'), [
            'subject' => 'New Office Chair Request',
            'company_id' => $this->company->company_id,
            'ticket_priority' => 'low',
            'description' => 'The backrest on my current chair is damaged.',
        ]);
        $response->assertRedirect(route('admin-tickets.index'));
        $this->assertDatabaseHas('xin_admin_tickets', [
            'subject' => 'New Office Chair Request',
            'ticket_priority' => 'low',
        ]);

        $ticket = AdminTicket::first();

        // 3. View detail
        $response = $this->actingAs($this->employee)->get(route('admin-tickets.show', $ticket->ticket_id));
        $response->assertStatus(200);
        $response->assertSee('New Office Chair Request');

        // 4. Update status by Admin
        $role = UserRole::create([
            'company_id' => 1,
            'role_name' => 'General Admin Lead',
            'role_access' => 'custom',
            'role_resources' => 'view.admin_tickets,edit.admin_tickets',
        ]);

        $adminUserObj = \App\Models\User::factory()->create();
        $adminUser = Employee::factory()->create([
            'user_id' => $adminUserObj->id,
            'user_role_id' => $role->id,
            'company_id' => $this->company->company_id,
        ]);

        $response = $this->actingAs($adminUser)->post(route('admin-tickets.status', $ticket->ticket_id), [
            'ticket_status' => '3',
            'remarks' => 'Approved, waiting for supply dispatch.',
        ]);

        $response->assertRedirect(route('admin-tickets.show', $ticket->ticket_id));
        $this->assertDatabaseHas('xin_admin_tickets', [
            'ticket_id' => $ticket->ticket_id,
            'ticket_status' => '3',
            'remarks' => 'Approved, waiting for supply dispatch.',
        ]);
    }
}
