<?php

namespace Tests\Feature\Settings;

use App\Models\EmailTemplate;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SettingManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_system_setting')) {
            Schema::create('xin_system_setting', function ($table) {
                $table->bigIncrements('setting_id');
                $table->string('application_name', 255)->default('Antigravity HR Portal');
                $table->string('default_currency', 20)->default('INR');
                $table->string('default_currency_symbol', 10)->default('₹');
                $table->string('support_email', 150)->default('support@company.com');
                $table->string('system_timezone', 100)->default('Asia/Kolkata');
                $table->integer('enable_registration')->default(0);
                $table->integer('module_recruitment')->default(1);
                $table->integer('module_training')->default(1);
                $table->integer('module_performance')->default(1);
                $table->integer('module_assets')->default(1);
                $table->integer('employee_manage_own_contact')->default(1);
                $table->integer('employee_manage_own_profile')->default(1);
                $table->integer('employee_manage_own_qualification')->default(1);
                $table->integer('employee_manage_own_document')->default(1);
                $table->string('footer_text', 500)->nullable();
                $table->string('updated_at', 50)->nullable();
            });
        }

        if (!Schema::hasTable('xin_user_roles')) {
            Schema::create('xin_user_roles', function ($table) {
                $table->bigIncrements('role_id');
                $table->integer('company_id')->default(1);
                $table->string('role_name', 100);
                $table->string('role_access', 50)->default('custom');
                $table->text('role_resources')->nullable();
                $table->string('created_at', 50)->nullable();
            });
        }

        if (!Schema::hasTable('xin_email_template')) {
            Schema::create('xin_email_template', function ($table) {
                $table->bigIncrements('template_id');
                $table->string('template_code', 100);
                $table->string('name', 255);
                $table->string('subject', 255);
                $table->text('message');
                $table->integer('status')->default(1);
            });
        }
    }

    public function test_system_settings_dashboard_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('system-settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Global System Settings');
    }

    public function test_user_roles_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user-roles.index'));

        $response->assertStatus(200);
        $response->assertSee('User Roles & Access Control');
    }

    public function test_email_templates_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('email-templates.index'));

        $response->assertStatus(200);
        $response->assertSee('Notification Email Templates');
    }

    public function test_system_settings_can_be_updated(): void
    {
        $user = User::factory()->create();
        SystemSetting::create(['application_name' => 'Old HR']);

        $response = $this->actingAs($user)->put(route('system-settings.update'), [
            'application_name' => 'Antigravity Enterprise HR',
            'support_email' => 'admin@enterprise.com',
            'default_currency' => 'USD',
            'default_currency_symbol' => '$',
            'system_timezone' => 'America/New_York',
            'module_recruitment' => 1,
        ]);

        $response->assertRedirect(route('system-settings.index'));
        $this->assertDatabaseHas('xin_system_setting', [
            'application_name' => 'Antigravity Enterprise HR',
            'support_email' => 'admin@enterprise.com',
            'default_currency' => 'USD',
        ]);
    }

    public function test_new_user_role_can_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('user-roles.store'), [
            'role_name' => 'Department Supervisor',
            'role_access' => 'custom',
            'role_resources' => ['employees', 'attendance', 'leave'],
        ]);

        $response->assertRedirect(route('user-roles.index'));
        $this->assertDatabaseHas('portal_roles', [
            'role_name' => 'Department Supervisor',
        ]);
    }

    public function test_email_template_can_be_updated(): void
    {
        $user = User::factory()->create();
        $template = EmailTemplate::create([
            'template_code' => 'TPL_LEAVE_APP',
            'name' => 'Leave Application Notification',
            'subject' => 'Old Subject',
            'message' => '<p>Old Message Body</p>',
            'status' => 1,
        ]);

        $response = $this->actingAs($user)->put(route('email-templates.update', $template->template_id), [
            'subject' => 'Leave Request Notification for Manager',
            'message' => '<p>Hello Manager, a new leave request was submitted.</p>',
        ]);

        $response->assertRedirect(route('email-templates.index'));
        $this->assertDatabaseHas('xin_email_template', [
            'template_id' => $template->template_id,
            'subject' => 'Leave Request Notification for Manager',
        ]);
    }

    public function test_api_v1_can_fetch_and_update_system_settings(): void
    {
        $user = User::factory()->create();
        SystemSetting::create(['application_name' => 'API HR Portal']);

        $getResponse = $this->actingAs($user)->getJson('/api/v1/system-settings');
        $getResponse->assertStatus(200);

        $putResponse = $this->actingAs($user)->putJson('/api/v1/system-settings', [
            'application_name' => 'API Portal Updated',
            'support_email' => 'api@company.com',
        ]);
        $putResponse->assertStatus(200);
        $this->assertDatabaseHas('xin_system_setting', [
            'application_name' => 'API Portal Updated',
        ]);
    }
}
