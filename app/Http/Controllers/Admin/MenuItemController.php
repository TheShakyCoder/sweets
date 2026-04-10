<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuItemController extends Controller
{
    public function index()
    {
        $items = MenuItem::whereNull('parent_id')
            ->with('children')
            ->orderBy('order')
            ->get();

        return Inertia::render('Admin/MenuItem/Index', [
            'items' => $items,
        ]);
    }

    public function create(Request $request)
    {
        $parentId = $request->query('parent_id');
        $parentItem = $parentId ? MenuItem::find($parentId) : null;
        $topLevelItems = MenuItem::whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return Inertia::render('Admin/MenuItem/Create', [
            'parentId' => $parentId,
            'parentItem' => $parentItem,
            'topLevelItems' => $topLevelItems,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'href' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'type' => 'required|in:link,submenu',
        ]);

        // If type is submenu, href should be null; if link, href is required
        if ($validated['type'] === 'link') {
            $request->validate(['href' => 'required|string']);
        } else {
            $validated['href'] = null;
        }

        // Determine next order number
        $parentId = $validated['parent_id'] ?? null;
        $nextOrder = MenuItem::query()
            ->when($parentId, fn ($q) => $q->where('parent_id', $parentId))
            ->when(!$parentId, fn ($q) => $q->whereNull('parent_id'))
            ->max('order') + 1;

        $validated['order'] = $nextOrder;

        MenuItem::create($validated);

        return redirect()->route('admin.menu-items.index')
            ->with('success', 'Menu item created successfully.');
    }

    public function edit(MenuItem $menuItem)
    {
        $topLevelItems = MenuItem::whereNull('parent_id')
            ->where('id', '!=', $menuItem->id)
            ->orderBy('order')
            ->get();

        return Inertia::render('Admin/MenuItem/Edit', [
            'menuItem' => $menuItem,
            'topLevelItems' => $topLevelItems,
        ]);
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'href' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'type' => 'required|in:link,submenu',
        ]);

        // If type is submenu, href should be null; if link, href is required
        if ($validated['type'] === 'link') {
            $request->validate(['href' => 'required|string']);
        } else {
            $validated['href'] = null;
        }

        $menuItem->update($validated);

        return redirect()->route('admin.menu-items.index')
            ->with('success', 'Menu item updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return redirect()->route('admin.menu-items.index')
            ->with('success', 'Menu item deleted successfully.');
    }
}
