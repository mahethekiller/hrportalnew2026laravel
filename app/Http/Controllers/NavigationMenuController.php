<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\NavigationMenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class NavigationMenuController extends Controller
{
    /**
     * Render navigation manager interface.
     */
    public function index(): View
    {
        $menus = NavigationMenu::whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        // Auto-discover all named application routes for dynamic selection
        $availableRoutes = collect(Route::getRoutes())
            ->map(fn($route) => $route->getName())
            ->filter()
            ->reject(fn($name) => str_starts_with($name, '_') || str_starts_with($name, 'ignition.') || str_starts_with($name, 'sanctum.'))
            ->sort()
            ->values();

        // Available permission resource keys
        $availableResources = [
            'employees', 'organization', 'leave', 'attendance', 'payroll',
            'performance', 'assets', 'recruitment', 'training', 'support_tickets',
            'hr_tickets', 'admin_tickets', 'announcements', 'settings', 'api_control', 'reports'
        ];

        return view('settings.navigation', compact('menus', 'availableRoutes', 'availableResources'));
    }

    /**
     * Store a newly created navigation menu item.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'icon' => 'nullable|string|max:100',
            'route_name' => 'nullable|string|max:150',
            'resource_key' => 'nullable|string|max:100',
            'parent_id' => 'nullable|integer',
        ]);

        $maxSortOrder = NavigationMenu::where('parent_id', $request->parent_id ?: null)->max('sort_order') ?? 0;

        NavigationMenu::create([
            'parent_id' => $request->parent_id ?: null,
            'title' => $request->title,
            'icon' => $request->icon ?: 'fa-solid fa-circle bullet-dot',
            'route_name' => $request->route_name ?: null,
            'resource_key' => $request->resource_key ?: null,
            'sort_order' => $maxSortOrder + 1,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('settings.navigation.index')
            ->with('success', 'Navigation menu node added successfully.');
    }

    /**
     * Update an existing navigation menu item.
     */
    public function update(Request $request, NavigationMenu $navigation): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'icon' => 'nullable|string|max:100',
            'route_name' => 'nullable|string|max:150',
            'resource_key' => 'nullable|string|max:100',
            'parent_id' => 'nullable|integer',
        ]);

        $navigation->update([
            'parent_id' => $request->parent_id ?: null,
            'title' => $request->title,
            'icon' => $request->icon ?: 'fa-solid fa-circle bullet-dot',
            'route_name' => $request->route_name ?: null,
            'resource_key' => $request->resource_key ?: null,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('settings.navigation.index')
            ->with('success', 'Navigation menu node updated successfully.');
    }

    /**
     * Delete a navigation menu item.
     */
    public function destroy(NavigationMenu $navigation): RedirectResponse
    {
        // Delete children if category node is deleted
        NavigationMenu::where('parent_id', $navigation->menu_id)->delete();
        $navigation->delete();

        return redirect()->route('settings.navigation.index')
            ->with('success', 'Navigation menu node deleted successfully.');
    }

    /**
     * Handle drag-and-drop structural updates via AJAX.
     */
    public function reorder(Request $request): JsonResponse
    {
        $structure = $request->input('structure', []);

        foreach ($structure as $index => $item) {
            $menuId = (int) $item['id'];
            $parentId = isset($item['parent_id']) ? (int) $item['parent_id'] : null;

            NavigationMenu::where('menu_id', $menuId)->update([
                'parent_id' => $parentId === 0 ? null : $parentId,
                'sort_order' => $index + 1,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Sidebar navigation hierarchy successfully re-ordered!',
        ]);
    }
}
