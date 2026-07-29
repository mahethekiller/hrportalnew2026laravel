<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\SupportTicket;
use App\Models\Department;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportTicketManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $employee;
    protected Department $department;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'NavigationMenuSeeder']);

        $company = \App\Models\Company::create([
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

        $this->department = \App\Models\Department::create([
            'department_id' => 1,
            'department_name' => 'IT Support',
            'company_id' => 1,
            'location_id' => 1,
            'employee_id' => 1,
            'added_by' => 1,
            'status' => 'active',
        ]);

        $user = \App\Models\User::factory()->create();
        $this->employee = Employee::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'department_id' => $this->department->department_id,
        ]);
    }

    public function test_tickets_directory_can_be_rendered(): void
    {
        $response = $this->actingAs($this->employee)->get(route('support-tickets.index'));

        $response->assertStatus(200);
        $response->assertSee('Support Tickets Helpdesk');
    }

    public function test_new_ticket_can_be_created(): void
    {
        $response = $this->actingAs($this->employee)->post(route('support-tickets.store'), [
            'subject' => 'Internet not working in block B',
            'department_id' => $this->department->department_id,
            'ticket_priority' => 'high',
            'description' => 'The wifi connection keeps dropping every 5 minutes.',
        ]);

        $response->assertRedirect(route('support-tickets.index'));
        $this->assertDatabaseHas('xin_support_tickets', [
            'subject' => 'Internet not working in block B',
            'ticket_priority' => 'high',
        ]);
    }

    public function test_comment_can_be_added(): void
    {
        $ticket = SupportTicket::create([
            'company_id' => 1,
            'ticket_code' => 'TK-TEST1',
            'subject' => 'Test Ticket',
            'employee_id' => $this->employee->user_id,
            'ticket_priority' => 'low',
            'department_id' => $this->department->department_id,
            'assigned_to' => '0',
            'message' => 'Detail message',
            'description' => 'Detail message',
            'ticket_remarks' => '',
            'ticket_status' => 'open',
            'ticket_note' => '',
            'created_at' => date('d-m-Y H:i:s'),
        ]);

        $response = $this->actingAs($this->employee)->post(route('support-tickets.comments', $ticket->ticket_id), [
            'reply_content' => 'This is a test reply/comment.',
        ]);

        $response->assertRedirect(route('support-tickets.show', $ticket->ticket_id));
        $this->assertDatabaseHas('xin_tickets_comments', [
            'ticket_id' => $ticket->ticket_id,
            'ticket_comments' => 'This is a test reply/comment.',
        ]);
    }

    public function test_status_can_be_updated_by_admin(): void
    {
        // Give the user view and edit permissions for tickets
        $role = UserRole::create([
            'company_id' => 1,
            'role_name' => 'IT Admin Partner',
            'role_access' => 'custom',
            'role_resources' => 'view.support_tickets,edit.support_tickets',
        ]);

        $adminUserObj = \App\Models\User::factory()->create();
        $adminUser = Employee::factory()->create([
            'user_id' => $adminUserObj->id,
            'user_role_id' => $role->id,
            'company_id' => 1,
            'department_id' => $this->department->department_id,
        ]);

        $ticket = SupportTicket::create([
            'company_id' => 1,
            'ticket_code' => 'TK-TEST2',
            'subject' => 'Test Ticket 2',
            'employee_id' => $adminUser->user_id,
            'ticket_priority' => 'medium',
            'department_id' => $this->department->department_id,
            'assigned_to' => '0',
            'message' => 'Detail message',
            'description' => 'Detail message',
            'ticket_remarks' => '',
            'ticket_status' => 'open',
            'ticket_note' => '',
            'created_at' => date('d-m-Y H:i:s'),
        ]);

        $response = $this->actingAs($adminUser)->post(route('support-tickets.status', $ticket->ticket_id), [
            'ticket_status' => 'resolved',
            'ticket_remarks' => 'Completed the wifi access point installation.',
        ]);

        $response->assertRedirect(route('support-tickets.show', $ticket->ticket_id));
        $this->assertDatabaseHas('xin_support_tickets', [
            'ticket_id' => $ticket->ticket_id,
            'ticket_status' => 'resolved',
            'ticket_remarks' => 'Completed the wifi access point installation.',
        ]);
    }
}
