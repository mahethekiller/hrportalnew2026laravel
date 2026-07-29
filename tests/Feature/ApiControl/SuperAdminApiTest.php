<?php

namespace Tests\Feature\ApiControl;

use App\Models\ApiAccessToken;
use App\Models\User;
use App\Models\WebhookTrigger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SuperAdminApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_api_access_tokens')) {
            Schema::create('xin_api_access_tokens', function ($table) {
                $table->bigIncrements('id');
                $table->string('username', 100);
                $table->string('accessToken', 255);
                $table->string('status', 50)->default('active');
                $table->string('added_date', 50)->nullable();
            });
        }

        if (!Schema::hasTable('xin_webhook_triggers')) {
            Schema::create('xin_webhook_triggers', function ($table) {
                $table->bigIncrements('webhook_id');
                $table->string('event_name', 100);
                $table->string('target_url', 255);
                $table->string('secret_key', 100)->nullable();
                $table->string('status', 50)->default('active');
                $table->string('created_at', 50)->nullable();
            });
        }
    }

    public function test_api_docs_suite_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('api.docs'));

        $response->assertStatus(200);
        $response->assertSee('REST API V1 OpenAPI Specification Suite');
    }

    public function test_api_tokens_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('api-tokens.index'));

        $response->assertStatus(200);
        $response->assertSee('API Access Keys Manager');
    }

    public function test_webhooks_directory_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('webhooks.index'));

        $response->assertStatus(200);
        $response->assertSee('Webhook Subscriptions Manager');
    }

    public function test_new_api_access_token_can_be_generated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('api-tokens.store'), [
            'username' => 'MobileApp_Client',
        ]);

        $response->assertRedirect(route('api-tokens.index'));
        $this->assertDatabaseHas('xin_api_access_tokens', [
            'username' => 'MobileApp_Client',
            'status' => 'active',
        ]);
    }

    public function test_api_access_token_can_be_revoked(): void
    {
        $user = User::factory()->create();
        $token = ApiAccessToken::create([
            'username' => 'ThirdPartyIntegration',
            'accessToken' => 'ag_live_1234567890',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->post(route('api-tokens.revoke', $token->id));

        $response->assertRedirect(route('api-tokens.index'));
        $this->assertDatabaseHas('xin_api_access_tokens', [
            'id' => $token->id,
            'status' => 'revoked',
        ]);
    }

    public function test_new_webhook_can_be_subscribed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('webhooks.store'), [
            'event_name' => 'employee.created',
            'target_url' => 'https://api.yourcompany.com/webhooks/employee',
        ]);

        $response->assertRedirect(route('webhooks.index'));
        $this->assertDatabaseHas('xin_webhook_triggers', [
            'event_name' => 'employee.created',
            'target_url' => 'https://api.yourcompany.com/webhooks/employee',
        ]);
    }

    public function test_webhook_status_can_be_toggled(): void
    {
        $user = User::factory()->create();
        $webhook = WebhookTrigger::create([
            'event_name' => 'payroll.processed',
            'target_url' => 'https://api.yourcompany.com/webhooks/payroll',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->post(route('webhooks.toggle', $webhook->webhook_id));

        $response->assertRedirect(route('webhooks.index'));
        $this->assertDatabaseHas('xin_webhook_triggers', [
            'webhook_id' => $webhook->webhook_id,
            'status' => 'disabled',
        ]);
    }
}
