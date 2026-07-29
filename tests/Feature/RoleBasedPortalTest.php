<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Company;
use App\Models\Department;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedPortalTest extends TestCase
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
            'department_name' => 'Human Resources',
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

    public function test_employee_ess_dashboard_can_be_rendered(): void
    {
        $response = $this->actingAs($this->employee)->get(route('my-portal.index'));
        $response->assertStatus(200);
    }

    public function test_employee_my_leaves_can_be_rendered(): void
    {
        $response = $this->actingAs($this->employee)->get(route('my-portal.leaves'));
        $response->assertStatus(200);
    }

    public function test_employee_performance_feedback_can_be_submitted(): void
    {
        $response = $this->actingAs($this->employee)->post(route('my-portal.performance_feedback.store'), [
            'ratings' => [1 => 5],
            'answers' => [1 => 'Exceeded quarterly sales targets.'],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas((new \App\Models\FeedbackAnswer)->getTable(), [
            'rating' => 5,
        ]);
    }

    public function test_employee_can_book_conference_room(): void
    {
        $response = $this->actingAs($this->employee)->post(route('my-portal.meetings.store'), [
            'meeting_title' => 'Product Roadmap Sync',
            'room_name' => 'Executive Boardroom A',
            'meeting_date' => date('Y-m-d'),
            'meeting_time' => '14:00',
            'note' => 'Projector required.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas((new \App\Models\Meeting)->getTable(), [
            'meeting_title' => 'Product Roadmap Sync',
        ]);
    }

    public function test_employee_can_submit_conveyance_claim(): void
    {
        $response = $this->actingAs($this->employee)->post(route('my-portal.conveyance.store'), [
            'travel_type' => 'Local Conveyance',
            'visit_place' => 'Downtown Client Site',
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d'),
            'expected_budget' => 45.50,
            'description' => 'Taxi fare for client meeting.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas((new \App\Models\EmployeeTravel)->getTable(), [
            'visit_place' => 'Downtown Client Site',
        ]);
    }

    public function test_manager_portal_dashboard_can_be_rendered(): void
    {
        $response = $this->actingAs($this->employee)->get(route('manager-portal.index'));
        $response->assertStatus(200);
    }

    public function test_manager_can_render_team_leaves(): void
    {
        $response = $this->actingAs($this->employee)->get(route('manager-portal.team_leaves'));
        $response->assertStatus(200);
    }
}
