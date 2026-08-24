<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use App\Services\ThemeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ThemeSystemTest extends TestCase
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

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_theme_settings_page_can_be_rendered(): void
    {
        $response = $this->get(route('settings.theme.index'));
        $response->assertStatus(200);
        $response->assertSee('Global Theme & Brand Settings');
    }

    public function test_admin_can_update_global_theme_settings(): void
    {
        $response = $this->post(route('settings.theme.update'), [
            'theme_color_profile' => 'sapphire',
            'custom_primary_hex' => '#2563EB',
            'font_family' => 'outfit',
            'theme_mode' => 'dark',
            'sidebar_style' => 'accent',
            'seasonal_accent' => 'diwali',
        ]);

        $response->assertRedirect(route('settings.theme.index'));
        $response->assertSessionHas('success');

        $themeService = app(ThemeService::class);
        $config = $themeService->getThemeConfig();

        $this->assertEquals('sapphire', $config['theme_color_profile']);
        $this->assertEquals('outfit', $config['font_family']);
        $this->assertEquals('dark', $config['theme_mode']);
        $this->assertEquals('diwali', $config['seasonal_accent']);
    }

    public function test_user_theme_preference_endpoint_returns_json_success(): void
    {
        $response = $this->postJson(route('settings.theme.preference'), [
            'profile' => 'violet',
            'mode' => 'dark',
            'font' => 'roboto',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Theme preferences saved successfully.',
        ]);
    }
}
