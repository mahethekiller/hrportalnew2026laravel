<?php

namespace Tests\Feature\Recruitment;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RecruitmentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_job_applications')) {
            Schema::create('xin_job_applications', function ($table) {
                $table->bigIncrements('application_id');
                $table->integer('job_id')->default(1);
                $table->string('candidate_name', 200);
                $table->string('email', 150);
                $table->string('gender', 20)->nullable();
                $table->string('experience', 50)->nullable();
                $table->integer('user_id')->nullable();
                $table->text('message')->nullable();
                $table->string('job_resume', 255)->nullable();
                $table->string('source', 100)->nullable();
                $table->string('sub_source', 100)->nullable();
                $table->string('referral_name', 150)->nullable();
                $table->string('date_cv_sourced', 50)->nullable();
                $table->string('company', 150)->nullable();
                $table->integer('department_id')->nullable();
                $table->string('current_location', 150)->nullable();
                $table->string('current_package', 50)->nullable();
                $table->string('expected_package', 50)->nullable();
                $table->string('contact_no', 50)->nullable();
                $table->string('notice_period', 50)->nullable();
                $table->string('change_reason', 500)->nullable();
                $table->string('current_company', 200)->nullable();
                $table->string('application_status', 50)->default('Applied');
                $table->text('application_remarks')->nullable();
                $table->text('hr_remarks')->nullable();
                $table->string('covid_status', 50)->nullable();
                $table->string('profile_picture', 255)->nullable();
                $table->string('reason_to_leave', 500)->nullable();
                $table->string('created_at', 50)->nullable();
                $table->string('added_by', 100)->nullable();
                $table->string('updated_by', 100)->nullable();
                $table->string('updated_date', 50)->nullable();
                $table->integer('show_status')->default(1);
                $table->text('remarks')->nullable();
            });
        }

        if (!Schema::hasTable('xin_job_interviews')) {
            Schema::create('xin_job_interviews', function ($table) {
                $table->bigIncrements('job_interview_id');
                $table->integer('job_id')->default(1);
                $table->integer('application_id');
                $table->integer('interviewers_id')->nullable();
                $table->string('interview_mode', 50)->default('Online Video Call');
                $table->string('interview_place', 200)->nullable();
                $table->string('interview_date', 50)->nullable();
                $table->string('interview_date2', 50)->nullable();
                $table->string('new_date', 50)->nullable();
                $table->string('next_round_date', 50)->nullable();
                $table->string('interview_time', 50)->nullable();
                $table->integer('interviewees_id')->nullable();
                $table->string('expected_doj', 50)->nullable();
                $table->string('offered_ctc', 100)->nullable();
                $table->text('description')->nullable();
                $table->text('remarks')->nullable();
                $table->string('status', 50)->default('Scheduled');
                $table->string('offer_status', 50)->nullable();
                $table->integer('salary_template_id')->nullable();
                $table->integer('convert_to_employee')->default(0);
                $table->integer('employee_id')->nullable();
                $table->string('added_by', 100)->nullable();
                $table->string('created_at', 50)->nullable();
                $table->string('updated_date', 50)->nullable();
                $table->string('updated_by', 100)->nullable();
                $table->integer('show_status')->default(1);
            });
        }

        if (!Schema::hasTable('xin_departments')) {
            Schema::create('xin_departments', function ($table) {
                $table->increments('department_id');
                $table->string('department_name', 150);
            });
        }

        if (!Schema::hasTable('xin_employees')) {
            Schema::create('xin_employees', function ($table) {
                $table->bigIncrements('user_id');
                $table->string('employee_id', 200)->nullable();
                $table->string('card_no', 100)->nullable();
                $table->string('first_name', 100);
                $table->string('last_name', 100);
                $table->string('email', 100)->nullable();
                $table->integer('department_id')->default(1);
                $table->integer('designation_id')->default(1);
                $table->integer('company_id')->default(1);
                $table->integer('is_active')->default(1);
            });
        }

        if (!Schema::hasTable('xin_office_shift')) {
            Schema::create('xin_office_shift', function ($table) {
                $table->bigIncrements('office_shift_id');
                $table->string('shift_name', 150)->default('Default Shift');
            });
        }

        if (!Schema::hasTable('xin_jobs')) {
            Schema::create('xin_jobs', function ($table) {
                $table->bigIncrements('job_id');
                $table->string('job_code', 100)->nullable();
                $table->string('job_title', 255);
                $table->integer('status')->default(1);
                $table->integer('show_status')->default(1);
            });
        }
    }

    public function test_recruitment_dashboard_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('recruitment-applications.index'));

        $response->assertStatus(200);
        $response->assertSee('Recruitment Candidate Pipeline');
    }

    public function test_interviews_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('recruitment-interviews.index'));

        $response->assertStatus(200);
        $response->assertSee('Scheduled Candidate Interviews');
    }

    public function test_candidate_application_can_be_submitted(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('recruitment-applications.store'), [
            'candidate_name' => 'Amit Verma',
            'email' => 'amit.verma@example.com',
            'contact_no' => '+91 9988776655',
            'experience' => '5 Years',
            'current_company' => 'Wipro',
            'expected_package' => '$85,000',
        ]);

        $response->assertRedirect(route('recruitment-applications.index'));
        $this->assertDatabaseHas('xin_job_applications', [
            'candidate_name' => 'Amit Verma',
            'email' => 'amit.verma@example.com',
        ]);
    }

    public function test_candidate_interview_can_be_scheduled(): void
    {
        $user = User::factory()->create();
        $application = JobApplication::create([
            'candidate_name' => 'Vikram Seth',
            'email' => 'vikram@example.com',
            'contact_no' => '9876543210',
            'application_status' => 'Applied',
            'show_status' => 1,
        ]);

        \Illuminate\Support\Facades\Mail::fake();

        $response = $this->actingAs($user)->post(route('recruitment-interviews.store'), [
            'application_id' => $application->application_id,
            'interview_mode' => 'Online Video Call',
            'interview_date' => '2026-08-15',
            'interview_time' => '14:00',
            'send_email_notification' => 1,
            'notify_candidate' => 1,
        ]);

        $response->assertRedirect(route('recruitment-interviews.index'));
        $this->assertDatabaseHas('xin_job_interviews', [
            'application_id' => $application->application_id,
            'interview_mode' => 'Online Video Call',
        ]);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\CandidateInterviewScheduledMail::class, function ($mail) use ($application) {
            return $mail->hasTo($application->email);
        });
    }

    public function test_candidate_can_be_converted_to_employee(): void
    {
        $user = User::factory()->create();
        $application = JobApplication::create([
            'candidate_name' => 'Karan Johar',
            'email' => 'karan.johar@example.com',
            'contact_no' => '9988776655',
            'application_status' => 'Applied',
            'show_status' => 1,
        ]);

        $interview = \App\Models\JobInterview::create([
            'application_id' => $application->application_id,
            'status' => 'confirmed',
            'show_status' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('recruitment-interviews.convert', $interview->job_interview_id));

        $response->assertStatus(302);
        $this->assertDatabaseHas('xin_employees', [
            'first_name' => 'Karan',
            'last_name' => 'Johar',
            'email' => 'karan.johar@example.com',
        ]);
        $this->assertDatabaseHas('xin_job_interviews', [
            'job_interview_id' => $interview->job_interview_id,
            'convert_to_employee' => 1,
        ]);
    }

    public function test_api_v1_can_fetch_and_create_job_applications(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.v1.job-applications.store'), [
            'candidate_name' => 'Priya Nair',
            'email' => 'priya.nair@example.com',
            'contact_no' => '+91 9123456789',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('message', 'Candidate application submitted successfully.');

        $getResponse = $this->actingAs($user)->getJson(route('api.v1.job-applications.index'));
        $getResponse->assertStatus(200);
    }

    public function test_candidate_application_can_be_updated(): void
    {
        $user = User::factory()->create();

        $application = JobApplication::create([
            'candidate_name' => 'Original Candidate',
            'email' => 'original@example.com',
            'contact_no' => '1234567890',
            'gender' => 'Male',
            'company' => 1,
            'application_status' => 'Applied',
            'show_status' => 1,
        ]);

        $response = $this->actingAs($user)->put(route('recruitment-applications.update', $application->application_id), [
            'candidate_name' => 'Updated Candidate Name',
            'email' => 'updated@example.com',
            'contact_no' => '9876543210',
            'gender' => 'Female',
            'experience' => '5 Years',
            'current_company' => 'New Tech Corp',
            'current_package' => '10 LPA',
            'expected_package' => '15 LPA',
        ]);

        $response->assertRedirect(route('recruitment-applications.index'));
        $this->assertDatabaseHas('xin_job_applications', [
            'application_id' => $application->application_id,
            'candidate_name' => 'Updated Candidate Name',
            'email' => 'updated@example.com',
            'gender' => 'Female',
            'experience' => '5 Years',
        ]);
    }
}
