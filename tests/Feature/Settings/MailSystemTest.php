<?php

namespace Tests\Feature\Settings;

use App\Models\Company;
use App\Models\User;
use App\Services\MailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MailSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('users')) {
            Schema::create('users', function ($table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xin_companies')) {
            Schema::create('xin_companies', function ($table) {
                $table->bigIncrements('company_id');
                $table->integer('type_id')->default(1)->nullable();
                $table->string('name', 255)->nullable();
                $table->string('trading_name', 255)->nullable();
                $table->string('username', 100)->nullable();
                $table->string('password', 255)->nullable();
                $table->string('registration_no', 100)->nullable();
                $table->string('government_tax', 100)->nullable();
                $table->string('email', 150)->nullable();
                $table->string('logo', 255)->nullable();
                $table->integer('is_active')->default(1)->nullable();
            });
        }

        if (!Schema::hasTable('xin_email_history')) {
            Schema::create('xin_email_history', function ($table) {
                $table->bigIncrements('id');
                $table->string('subject', 255)->nullable();
                $table->text('message')->nullable();
                $table->string('from_email', 150)->nullable();
                $table->text('to_emails')->nullable();
                $table->string('sent_date', 50)->nullable();
                $table->string('mail_type', 50)->nullable();
                $table->integer('mail_type_id')->nullable();
                $table->integer('user_id')->nullable();
                $table->integer('show_status')->default(1);
            });
        }
        
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_smtp_profiles_page_can_be_rendered(): void
    {
        $response = $this->get(route('smtp-profiles.index'));
        $response->assertStatus(200);
        $response->assertSee('Multi-SMTP Profiles');
    }

    public function test_smtp_profile_can_be_created_and_saved(): void
    {
        $response = $this->post(route('smtp-profiles.store'), [
            'name' => 'HR Test Mailer',
            'host' => 'smtp.test.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'hr@test.com',
            'password' => 'secret123',
            'from_address' => 'hr@test.com',
            'from_name' => 'HR Team',
            'is_active' => 'on',
        ]);

        $response->assertRedirect(route('smtp-profiles.index'));
        $response->assertSessionHas('success');

        $mailService = app(MailService::class);
        $profiles = $mailService->getSmtpProfiles();

        $found = false;
        foreach ($profiles as $profile) {
            if (($profile['name'] ?? '') === 'HR Test Mailer') {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }

    public function test_global_routing_and_extra_ccs_can_be_updated(): void
    {
        $response = $this->post(route('smtp-profiles.routing'), [
            'global_enabled' => 'on',
            'module_switch_leave' => 'on',
            'profile_leave' => 'default',
            'extra_cc_leave' => 'global-hr@test.com, audit@test.com',
        ]);

        $response->assertRedirect(route('smtp-profiles.index'));
        $response->assertSessionHas('success');

        $mailService = app(MailService::class);
        $config = $mailService->getMailConfig();

        $this->assertTrue(!empty($config['global_enabled']));
        $this->assertEquals('global-hr@test.com, audit@test.com', $config['global_extra_ccs']['leave'] ?? '');
    }

    public function test_company_specific_extra_cc_routing_can_be_saved(): void
    {
        $companyId = \DB::table('xin_companies')->insertGetId([
            'type_id' => 1,
            'name' => 'I2K2 Test Company',
            'trading_name' => 'I2K2 Test',
            'username' => 'i2k2test',
            'password' => 'secret',
            'registration_no' => '123456',
            'government_tax' => '0',
            'email' => 'info@i2k2.com',
            'logo' => 'logo.png',
            'contact_number' => '1234567890',
            'website_url' => 'https://i2k2.com',
            'address_1' => 'Address 1',
            'address_2' => 'Address 2',
            'city' => 'Noida',
            'state' => 'UP',
            'zipcode' => '201301',
            'country' => 'India',
            'is_active' => 1,
            'added_by' => 1
        ]);

        $response = $this->post(route('smtp-profiles.company-routing'), [
            'company_id' => $companyId,
            'company_extra_cc_leave' => 'company1-hr@test.com',
            'company_extra_cc_ticket' => 'company1-helpdesk@test.com',
        ]);

        $response->assertRedirect(route('smtp-profiles.index'));
        $response->assertSessionHas('success');

        $mailService = app(MailService::class);
        $extraCcs = $mailService->resolveExtraCcEmails('leave', $companyId);

        $this->assertContains('company1-hr@test.com', $extraCcs);
    }

    public function test_email_logs_page_can_be_rendered(): void
    {
        $response = $this->get(route('email-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('Email Delivery Audit Logs');
    }
}
