<?php

namespace Tests\Feature\Training;

use App\Models\Trainer;
use App\Models\TrainingSession;
use App\Models\TrainingType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TrainingManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_training_types')) {
            Schema::create('xin_training_types', function ($table) {
                $table->bigIncrements('training_type_id');
                $table->integer('company_id')->default(1);
                $table->string('type', 150);
                $table->integer('status')->default(1);
                $table->string('created_at', 50)->nullable();
            });
        }

        if (!Schema::hasTable('xin_trainers')) {
            Schema::create('xin_trainers', function ($table) {
                $table->bigIncrements('trainer_id');
                $table->integer('company_id')->default(1);
                $table->string('first_name', 100);
                $table->string('last_name', 100);
                $table->string('contact_number', 50)->nullable();
                $table->string('email', 150)->nullable();
                $table->integer('designation_id')->default(1);
                $table->string('expertise', 255)->nullable();
                $table->text('address')->nullable();
                $table->string('status', 50)->default('active');
                $table->string('created_at', 50)->nullable();
            });
        }

        if (!Schema::hasTable('xin_training')) {
            Schema::create('xin_training', function ($table) {
                $table->bigIncrements('training_id');
                $table->integer('company_id')->default(1);
                $table->integer('employee_id');
                $table->integer('training_type_id');
                $table->integer('trainer_id');
                $table->string('start_date', 50);
                $table->string('finish_date', 50);
                $table->decimal('training_cost', 10, 2)->default(0.00);
                $table->integer('training_status')->default(0);
                $table->text('description')->nullable();
                $table->string('performance', 255)->nullable();
                $table->text('remarks')->nullable();
                $table->string('created_at', 50)->nullable();
            });
        }

        if (!Schema::hasTable('xin_employees')) {
            Schema::create('xin_employees', function ($table) {
                $table->bigIncrements('user_id');
                $table->string('employee_id', 200)->nullable();
                $table->string('first_name', 100);
                $table->string('last_name', 100);
                $table->string('email', 100)->nullable();
            });
        }
    }

    public function test_training_dashboard_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('training-sessions.index'));

        $response->assertStatus(200);
        $response->assertSee('Training & Development Sessions');
    }

    public function test_trainers_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('trainers.index'));

        $response->assertStatus(200);
        $response->assertSee('Instructors & Trainers Directory');
    }

    public function test_new_trainer_can_be_registered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('trainers.store'), [
            'first_name' => 'Aditya',
            'last_name' => 'Roy',
            'email' => 'aditya.trainer@example.com',
            'contact_number' => '9876543210',
            'expertise' => 'Cyber Security',
        ]);

        $response->assertRedirect(route('trainers.index'));
        $this->assertDatabaseHas('xin_trainers', [
            'first_name' => 'Aditya',
            'last_name' => 'Roy',
            'email' => 'aditya.trainer@example.com',
        ]);
    }

    public function test_new_training_session_can_be_scheduled(): void
    {
        $user = User::factory()->create();
        $type = TrainingType::create(['type' => 'Fullstack Laravel Mastery']);
        $trainer = Trainer::create(['first_name' => 'Rohan', 'last_name' => 'Mehta', 'email' => 'rohan@example.com']);

        $response = $this->actingAs($user)->post(route('training-sessions.store'), [
            'employee_id' => 1,
            'training_type_id' => $type->training_type_id,
            'trainer_id' => $trainer->trainer_id,
            'start_date' => '2026-08-01',
            'finish_date' => '2026-08-10',
            'training_cost' => 15000.00,
            'description' => 'Advanced Laravel & Vue 3 Development Workshop',
        ]);

        $response->assertRedirect(route('training-sessions.index'));
        $this->assertDatabaseHas('xin_training', [
            'training_type_id' => $type->training_type_id,
            'trainer_id' => $trainer->trainer_id,
            'training_cost' => 15000.00,
        ]);
    }

    public function test_api_v1_can_fetch_and_create_training_sessions(): void
    {
        $user = User::factory()->create();
        $type = TrainingType::create(['type' => 'Cloud Devops']);
        $trainer = Trainer::create(['first_name' => 'Vikram', 'last_name' => 'Singh', 'email' => 'vikram@example.com']);

        $response = $this->actingAs($user)->postJson(route('api.v1.training-sessions.store'), [
            'employee_id' => 1,
            'training_type_id' => $type->training_type_id,
            'trainer_id' => $trainer->trainer_id,
            'start_date' => '2026-09-01',
            'finish_date' => '2026-09-05',
            'training_cost' => 8000.00,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('message', 'Training session scheduled successfully.');

        $getResponse = $this->actingAs($user)->getJson(route('api.v1.training-sessions.index'));
        $getResponse->assertStatus(200);
    }
}
