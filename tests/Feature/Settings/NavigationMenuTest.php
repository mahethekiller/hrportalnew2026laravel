<?php

namespace Tests\Feature\Settings;

use App\Models\NavigationMenu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NavigationMenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('xin_navigation_menus')) {
            Schema::create('xin_navigation_menus', function ($table) {
                $table->bigIncrements('menu_id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('title', 100);
                $table->string('icon', 100)->nullable();
                $table->string('route_name', 150)->nullable();
                $table->string('resource_key', 100)->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
            });
        }
    }

    public function test_navigation_manager_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('settings.navigation.index'));

        $response->assertStatus(200);
        $response->assertSee('Sidebar Dynamic Menu Manager');
    }

    public function test_navigation_hierarchy_can_be_reordered(): void
    {
        $user = User::factory()->create();
        $root = NavigationMenu::create(['title' => 'Talent System', 'sort_order' => 1]);
        $child = NavigationMenu::create(['title' => 'Training', 'parent_id' => null, 'sort_order' => 2]);

        $response = $this->actingAs($user)->postJson(route('settings.navigation.reorder'), [
            'structure' => [
                [
                    'id' => $child->menu_id,
                    'parent_id' => $root->menu_id,
                ]
            ]
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('xin_navigation_menus', [
            'menu_id' => $child->menu_id,
            'parent_id' => $root->menu_id,
        ]);
    }
}
