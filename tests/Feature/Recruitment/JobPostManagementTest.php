<?php

namespace Tests\Feature\Recruitment;

use App\Models\JobPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class JobPostManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_jobs')) {
            Schema::create('xin_jobs', function ($table) {
                $table->bigIncrements('job_id');
                $table->integer('company_id')->default(1);
                $table->string('job_code', 100)->nullable();
                $table->string('job_title', 255);
                $table->integer('designation_id')->default(1);
                $table->string('job_type', 100)->default('Full Time');
                $table->integer('is_featured')->default(0);
                $table->integer('job_vacancy')->default(1);
                $table->string('gender', 50)->nullable();
                $table->string('minimum_experience', 50)->nullable();
                $table->string('maximum_experience', 50)->nullable();
                $table->string('start_date', 50)->nullable();
                $table->string('date_of_closing', 50)->nullable();
                $table->string('department', 150)->nullable();
                $table->string('priority', 50)->default('Medium');
                $table->string('hiring_manager', 150)->nullable();
                $table->string('job_location', 255)->nullable();
                $table->text('short_description')->nullable();
                $table->text('long_description')->nullable();
                $table->integer('status')->default(1);
                $table->integer('show_on_website')->default(1);
                $table->string('created_at', 50)->nullable();
                $table->string('added_by', 100)->nullable();
                $table->string('updated_date', 50)->nullable();
                $table->string('updated_by', 100)->nullable();
                $table->integer('show_status')->default(1);
            });
        }

        if (!Schema::hasTable('xin_job_applications')) {
            Schema::create('xin_job_applications', function ($table) {
                $table->bigIncrements('application_id');
                $table->integer('job_id')->default(1);
                $table->string('candidate_name', 200);
                $table->string('email', 100)->nullable();
                $table->string('application_status', 100)->default('Applied');
                $table->integer('show_status')->default(1);
            });
        }

        if (!Schema::hasTable('xin_job_codes')) {
            Schema::create('xin_job_codes', function ($table) {
                $table->bigIncrements('job_code_id');
                $table->string('job_code', 100);
                $table->string('position', 255);
                $table->string('status', 50)->default('active');
            });
        }
    }

    public function test_job_posts_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('recruitment-job-posts.index'));

        $response->assertStatus(200);
        $response->assertSee('Job Openings & Requisitions');
    }

    public function test_new_job_requisition_can_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('recruitment-job-posts.store'), [
            'job_title' => 'Senior Tech Lead',
            'job_code' => 'JOB-2026-999',
            'job_type' => 'Full Time',
            'job_vacancy' => 3,
            'job_location' => 'Bangalore, India',
            'minimum_experience' => '5',
            'maximum_experience' => '8',
            'status' => 1,
        ]);

        $response->assertRedirect(route('recruitment-job-posts.index'));
        $this->assertDatabaseHas('xin_jobs', [
            'job_title' => 'Senior Tech Lead',
            'job_code' => 'JOB-2026-999',
            'job_vacancy' => 3,
        ]);
    }

    public function test_api_v1_can_fetch_and_create_job_posts(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.v1.job-posts.store'), [
            'job_title' => 'DevOps Engineer',
            'job_vacancy' => 2,
            'status' => 1,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('message', 'Job opening requisition published successfully.');

        $getResponse = $this->actingAs($user)->getJson(route('api.v1.job-posts.index'));
        $getResponse->assertStatus(200);
    }

    public function test_job_requisition_can_be_updated(): void
    {
        $user = User::factory()->create();
        $job = JobPost::create([
            'job_title' => 'Junior QA Engineer',
            'job_vacancy' => 1,
            'status' => 1,
        ]);

        $response = $this->actingAs($user)->put(route('recruitment-job-posts.update', $job->job_id), [
            'job_title' => 'Senior Lead QA Engineer',
            'job_vacancy' => 4,
            'status' => 0,
        ]);

        $response->assertRedirect(route('recruitment-job-posts.index'));
        $this->assertDatabaseHas('xin_jobs', [
            'job_id' => $job->job_id,
            'job_title' => 'Senior Lead QA Engineer',
            'job_vacancy' => 4,
        ]);
    }
}
