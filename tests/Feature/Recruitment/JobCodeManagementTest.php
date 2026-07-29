<?php

namespace Tests\Feature\Recruitment;

use App\Models\JobCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class JobCodeManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_job_codes')) {
            Schema::create('xin_job_codes', function ($table) {
                $table->bigIncrements('job_code_id');
                $table->integer('company_id')->default(1);
                $table->string('job_code', 100);
                $table->string('position', 255);
                $table->string('added_by', 100)->nullable();
                $table->string('added_date', 50)->nullable();
                $table->string('updated_by', 100)->nullable();
                $table->string('updated_date', 50)->nullable();
                $table->integer('status')->default(1);
            });
        }
    }

    public function test_job_codes_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('recruitment-job-codes.index'));

        $response->assertStatus(200);
        $response->assertSee('Company Job Code Tags');
    }

    public function test_new_job_code_tag_can_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('recruitment-job-codes.store'), [
            'job_code' => 'JOB-DEV-888',
            'position' => 'Principal Software Architect',
            'status' => 1,
        ]);

        $response->assertRedirect(route('recruitment-job-codes.index'));
        $this->assertDatabaseHas('xin_job_codes', [
            'job_code' => 'JOB-DEV-888',
            'position' => 'Principal Software Architect',
        ]);
    }

    public function test_job_code_tag_can_be_updated(): void
    {
        $user = User::factory()->create();
        $code = JobCode::create([
            'job_code' => 'JOB-DEV-777',
            'position' => 'Junior Developer',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->put(route('recruitment-job-codes.update', $code->job_code_id), [
            'job_code' => 'JOB-DEV-777-REV',
            'position' => 'Mid-Level Developer',
            'status' => 'inactive',
        ]);

        $response->assertRedirect(route('recruitment-job-codes.index'));
        $this->assertDatabaseHas('xin_job_codes', [
            'job_code_id' => $code->job_code_id,
            'position' => 'Mid-Level Developer',
            'status' => 'inactive',
        ]);
    }
}
