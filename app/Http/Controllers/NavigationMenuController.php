<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\NavigationMenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        return view('settings.navigation', compact('menus'));
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
