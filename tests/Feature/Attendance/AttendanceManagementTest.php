<?php

namespace Tests\Feature\Attendance;

use App\Models\Employee;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AttendanceManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_emp_today_attendance')) {
            Schema::create('xin_emp_today_attendance', function ($table) {
                $table->bigIncrements('id');
                $table->string('card_no', 100);
                $table->string('punch_date', 50);
                $table->string('check_in_datetime', 50)->nullable();
                $table->string('check_out_datetime', 50)->nullable();
                $table->string('badgenumber', 100)->nullable();
                $table->string('check_in_time', 50)->nullable();
                $table->string('check_out_time', 50)->nullable();
                $table->string('show_status', 50)->default('Present');
            });
        }

        if (!Schema::hasTable('xin_clocking')) {
            Schema::create('xin_clocking', function ($table) {
                $table->bigIncrements('id');
                $table->integer('userid');
                $table->string('clock_in', 50)->nullable();
                $table->string('clock_out', 50)->nullable();
                $table->text('description')->nullable();
                $table->string('created_at', 50)->nullable();
                $table->string('show_status', 50)->nullable();
            });
        }

        if (!Schema::hasTable('xin_office_shift')) {
            Schema::create('xin_office_shift', function ($table) {
                $table->bigIncrements('office_shift_id');
                $table->integer('company_id')->default(1);
                $table->string('shift_name', 150);
                $table->integer('default_shift')->default(0);
                $table->string('monday_in_time', 50)->nullable();
                $table->string('monday_out_time', 50)->nullable();
                $table->string('tuesday_in_time', 50)->nullable();
                $table->string('tuesday_out_time', 50)->nullable();
                $table->string('wednesday_in_time', 50)->nullable();
                $table->string('wednesday_out_time', 50)->nullable();
                $table->string('thursday_in_time', 50)->nullable();
                $table->string('thursday_out_time', 50)->nullable();
                $table->string('friday_in_time', 50)->nullable();
                $table->string('friday_out_time', 50)->nullable();
                $table->string('saturday_in_time', 50)->nullable();
                $table->string('saturday_out_time', 50)->nullable();
                $table->string('sunday_in_time', 50)->nullable();
                $table->string('sunday_out_time', 50)->nullable();
                $table->string('created_at', 50)->nullable();
            });
        }

        if (!Schema::hasTable('xin_employees')) {
            Schema::create('xin_employees', function ($table) {
                $table->increments('user_id');
                $table->string('employee_id', 200)->nullable();
                $table->string('card_no', 100)->nullable();
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

    public function test_attendance_dashboard_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('attendance.index'));

        $response->assertStatus(200);
        $response->assertSee('Attendance');
    }

    public function test_office_shifts_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('office-shifts.index'));

        $response->assertStatus(200);
        $response->assertSee('Office Shift Roster');
    }

    public function test_employee_can_clock_in_and_clock_out_for_wfh(): void
    {
        $user = User::factory()->create();

        $responseClockIn = $this->actingAs($user)->post(route('attendance.wfh-clock-in'), [
            'description' => 'Development work from home',
        ]);

        $responseClockIn->assertRedirect(route('attendance.index'));
        $this->assertDatabaseHas('xin_clocking', [
            'userid' => $user->id,
            'description' => 'Development work from home',
        ]);

        $responseClockOut = $this->actingAs($user)->post(route('attendance.wfh-clock-out'));
        $responseClockOut->assertRedirect(route('attendance.index', ['tab' => 'wfh']));
    }

    public function test_manual_office_punch_can_be_recorded(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('attendance.manual'), [
            'card_no' => 'CARD-999',
            'punch_date' => '2026-08-01',
            'check_in_time' => '09:00',
            'check_out_time' => '18:00',
            'show_status' => 'Present',
        ]);

        $response->assertRedirect(route('attendance.index'));
        $this->assertDatabaseHas('xin_emp_today_attendance', [
            'card_no' => 'CARD-999',
            'punch_date' => '2026-08-01',
            'show_status' => 'Present',
        ]);
    }

    public function test_api_v1_can_fetch_and_clock_in_attendance(): void
    {
        $user = User::factory()->create();

        $responseClockIn = $this->actingAs($user)->postJson(route('api.v1.wfh.clock-in'), [
            'description' => 'API WFH Session',
        ]);

        $responseClockIn->assertStatus(201)
                        ->assertJsonPath('message', 'WFH Clock-In registered successfully.');

        $getResponse = $this->actingAs($user)->getJson(route('api.v1.attendance.index'));
        $getResponse->assertStatus(200);
    }
}
