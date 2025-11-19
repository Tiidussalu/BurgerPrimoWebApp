<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class MenuItemController extends Controller
{
    public function index(Request $request): Response
    {
        // Check admin access
        if (!auth()->user()?->is_admin) {
            abort(403, 'Unauthorized - Admin access required');
        }

        $query = MenuItem::with('category');

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->orderBy('sort_order')->paginate(20);

        $categories = MenuCategory::orderBy('sort_order')->get();

        return Inertia::render('Admin/Menu/Items/Index', [
            'items' => $items,
            'categories' => $categories,
            'filters' => $request->only(['category_id', 'search']),
        ]);
    }

    public function create(): Response
    {
        // Check admin access
        if (!auth()->user()?->is_admin) {
            abort(403, 'Unauthorized - Admin access required');
        }

        $categories = MenuCategory::orderBy('sort_order')->get();

        return Inertia::render('Admin/Menu/Items/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        // Check admin access
        if (!auth()->user()?->is_admin) {
            abort(403, 'Unauthorized - Admin access required');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'size' => 'nullable|string|max:100',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        MenuItem::create($validated);

        return redirect()->route('admin.menu.items.index')
            ->with('success', 'Menu item created successfully!');
    }

    public function edit(MenuItem $item): Response
    {
        // Check admin access
        if (!auth()->user()?->is_admin) {
            abort(403, 'Unauthorized - Admin access required');
        }

        $categories = MenuCategory::orderBy('sort_order')->get();

        return Inertia::render('Admin/Menu/Items/Edit', [
            'item' => $item,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, MenuItem $item)
    {
        // Check admin access
        if (!auth()->user()?->is_admin) {
            abort(403, 'Unauthorized - Admin access required');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'size' => 'nullable|string|max:100',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        $item->update($validated);

        return redirect()->route('admin.menu.items.index')
            ->with('success', 'Menu item updated successfully!');
    }

    public function destroy(MenuItem $item)
    {
        // Check admin access
        if (!auth()->user()?->is_admin) {
            abort(403, 'Unauthorized - Admin access required');
        }

        // Delete image if exists
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('admin.menu.items.index')
            ->with('success', 'Menu item deleted successfully!');
    }

    public function updateOrder(Request $request)
    {
        // Check admin access
        if (!auth()->user()?->is_admin) {
            abort(403, 'Unauthorized - Admin access required');
        }

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['items'] as $itemData) {
            MenuItem::where('id', $itemData['id'])
                ->update(['sort_order' => $itemData['sort_order']]);
        }

        return redirect()->back()->with('success', 'Item order updated!');
    }
}