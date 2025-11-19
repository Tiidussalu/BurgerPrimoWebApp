<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    public function index(): Response
    {
        $categories = MenuCategory::with(['activeItems' => function ($query) {
            $query->ordered();
        }])
            ->active()
            ->ordered()
            ->get();

        $featuredItems = MenuItem::with('category')
            ->active()
            ->featured()
            ->ordered()
            ->limit(10)
            ->get();

        return Inertia::render('Menu/Index', [
            'categories' => $categories,
            'featuredItems' => $featuredItems,
        ]);
    }

    public function category(string $slug): Response
    {
        $category = MenuCategory::with(['activeItems' => function ($query) {
            $query->ordered();
        }])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        $categories = MenuCategory::active()->ordered()->get();

        return Inertia::render('Menu/Category', [
            'category' => $category,
            'categories' => $categories,
        ]);
    }
}