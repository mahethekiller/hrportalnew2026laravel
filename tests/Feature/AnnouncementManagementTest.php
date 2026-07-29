<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Announcement;
use App\Models\Company;
use App\Models\Department;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AnnouncementManagementTest extends TestCase
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

    public function test_announcements_directory_can_be_rendered(): void
    {
        $response = $this->actingAs($this->employee)->get(route('announcements.index'));
        $response->assertStatus(200);
    }

    public function test_announcement_can_be_published_by_admin(): void
    {
        $role = UserRole::create([
            'company_id' => 1,
            'role_name' => 'HR Communications Lead',
            'role_access' => 'custom',
            'role_resources' => 'view.announcements,edit.announcements',
        ]);

        $adminUserObj = \App\Models\User::factory()->create();
        $adminUser = Employee::factory()->create([
            'user_id' => $adminUserObj->id,
            'user_role_id' => $role->id,
            'company_id' => $this->company->company_id,
        ]);

        $response = $this->actingAs($adminUser)->post(route('announcements.store'), [
            'title' => 'Annual Strategy Meeting 2026',
            'announcement_type' => 'Event',
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-07',
            'company_id' => $this->company->company_id,
            'department_id' => 0,
            'summary' => 'Join us for the annual strategy kickoff.',
            'description' => 'Detailed strategy meeting agenda and schedules.',
        ]);

        $response->assertRedirect(route('announcements.index'));
        $this->assertDatabaseHas((new Announcement)->getTable(), [
            'title' => 'Annual Strategy Meeting 2026',
            'announcement_type' => 'Event',
        ]);
    }

    public function test_announcement_detail_can_be_viewed(): void
    {
        $table = (new Announcement)->getTable();
        $data = [
            'title' => 'Office Policy Update',
            'announcement_type' => 'Policy',
            'acceptance_message' => '',
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-15',
            'company_id' => $this->company->company_id,
            'department_id' => 0,
            'published_by' => 'HR Admin',
            'summary' => 'Updated remote work guidelines.',
            'description' => 'Full details regarding new remote work policies.',
            'image' => '',
            'is_active' => 1,
        ];
        if (Schema::hasColumn($table, 'created_at')) {
            $data['created_at'] = date('d-m-Y H:i:s');
        }

        $announcement = Announcement::create($data);

        $response = $this->actingAs($this->employee)->get(route('announcements.show', $announcement->getKey()));
        $response->assertStatus(200);
        $response->assertSee('Office Policy Update');
    }
}
