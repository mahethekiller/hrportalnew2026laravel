<?php

namespace Tests\Feature\Performance;

use App\Models\PerformanceAppraisal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PerformanceManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_performance_appraisal')) {
            Schema::create('xin_performance_appraisal', function ($table) {
                $table->bigIncrements('performance_appraisal_id');
                $table->integer('company_id')->default(1);
                $table->integer('employee_id');
                $table->integer('manager_id')->default(1);
                $table->string('appraisal_year_month', 20)->default('2026-08');
                $table->decimal('customer_experience', 5, 2)->default(4);
                $table->decimal('marketing', 5, 2)->default(4);
                $table->decimal('management', 5, 2)->default(4);
                $table->decimal('administration', 5, 2)->default(4);
                $table->decimal('presentation_skill', 5, 2)->default(4);
                $table->decimal('quality_of_work', 5, 2)->default(4);
                $table->decimal('efficiency', 5, 2)->default(4);
                $table->decimal('integrity', 5, 2)->default(4);
                $table->decimal('professionalism', 5, 2)->default(4);
                $table->decimal('team_work', 5, 2)->default(4);
                $table->decimal('teamwork', 5, 2)->nullable();
                $table->decimal('critical_thinking', 5, 2)->default(4);
                $table->decimal('conflict_management', 5, 2)->default(4);
                $table->decimal('attendance', 5, 2)->default(4);
                $table->decimal('job_knowledge', 5, 2)->default(4);
                $table->decimal('communication', 5, 2)->default(4);
                $table->decimal('problem_solving', 5, 2)->default(4);
                $table->decimal('ability_to_meet_deadline', 5, 2)->default(4);
                $table->text('remarks')->nullable();
                $table->text('area_strength')->nullable();
                $table->text('area_imp')->nullable();
                $table->text('future_goals')->nullable();
                $table->string('added_by', 100)->nullable();
                $table->string('created_at', 50)->nullable();
                $table->integer('show_status')->default(1);
            });
        }

        if (!Schema::hasTable('xin_performance_indicator')) {
            Schema::create('xin_performance_indicator', function ($table) {
                $table->bigIncrements('performance_indicator_id');
                $table->integer('company_id')->default(1);
                $table->integer('designation_id')->default(1);
                $table->decimal('quality_of_work', 5, 2)->default(4);
                $table->decimal('efficiency', 5, 2)->default(4);
                $table->string('added_by', 100)->nullable();
                $table->string('created_at', 50)->nullable();
            });
        }

        if (!Schema::hasTable('xin_employees')) {
            Schema::create('xin_employees', function ($table) {
                $table->increments('user_id');
                $table->string('employee_id', 200)->nullable();
                $table->string('first_name', 100);
                $table->string('last_name', 100);
                $table->string('email', 100)->nullable();
                $table->integer('department_id')->default(1);
                $table->integer('designation_id')->default(1);
                $table->integer('company_id')->default(1);
                $table->integer('is_active')->default(1);
            });
        }

        if (!Schema::hasTable('xin_designations')) {
            Schema::create('xin_designations', function ($table) {
                $table->increments('designation_id');
                $table->string('designation_name', 150);
            });
        }

        if (!Schema::hasTable('xin_office_shift')) {
            Schema::create('xin_office_shift', function ($table) {
                $table->bigIncrements('office_shift_id');
                $table->string('shift_name', 150)->default('Default Shift');
            });
        }
    }

    public function test_performance_dashboard_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('performance-appraisals.index'));

        $response->assertStatus(200);
        $response->assertSee('Performance Appraisals');
    }

    public function test_performance_indicators_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('performance-indicators.index'));

        $response->assertStatus(200);
        $response->assertSee('Performance Indicators');
    }

    public function test_performance_appraisal_can_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('performance-appraisals.store'), [
            'employee_id' => 1,
            'appraisal_year_month' => '2026-08',
            'quality_of_work' => 5,
            'efficiency' => 4,
            'job_knowledge' => 5,
            'teamwork' => 4,
            'communication' => 4,
            'problem_solving' => 5,
            'area_strength' => 'Exceptional code quality and technical leadership',
        ]);

        $response->assertRedirect(route('performance-appraisals.index'));
        $this->assertDatabaseHas((new \App\Models\PerformanceAppraisal)->getTable(), [
            'employee_id' => 1,
            'quality_of_work' => 5,
        ]);
    }

    public function test_performance_report_card_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $appraisal = PerformanceAppraisal::create([
            'employee_id' => 1,
            'appraisal_year_month' => '2026-08',
            'quality_of_work' => 5,
            'efficiency' => 4,
            'show_status' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('performance-appraisals.show', $appraisal->getKey()));

        $response->assertStatus(200);
        $response->assertSee('Performance Review Report Card');
    }

    public function test_api_v1_can_fetch_and_create_appraisals(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.v1.performance-appraisals.store'), [
            'employee_id' => 1,
            'appraisal_year_month' => '2026-08',
            'quality_of_work' => 4,
            'efficiency' => 4,
            'job_knowledge' => 4,
            'teamwork' => 4,
            'communication' => 4,
            'problem_solving' => 4,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('message', 'Performance Appraisal recorded successfully.');

        $getResponse = $this->actingAs($user)->getJson(route('api.v1.performance-appraisals.index'));
        $getResponse->assertStatus(200);
    }
}
