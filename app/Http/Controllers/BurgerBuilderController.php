<?php

namespace App\Http\Controllers;

use App\Models\CustomBurger;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BurgerBuilderController extends Controller
{
    public function index(): Response
    {
        $ingredients = Ingredient::where('is_available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        $favorites = auth()->user()
            ->customBurgers()
            ->where('is_favorite', true)
            ->with('ingredients')
            ->get();

        return Inertia::render('BurgerBuilder/Index', [
            'ingredients' => $ingredients,
            'favorites' => $favorites,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|integer|min:1|max:10',
            'is_favorite' => 'boolean',
        ]);

        $burger = CustomBurger::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'total_price' => 0, // Will be calculated
            'is_favorite' => $validated['is_favorite'] ?? false,
        ]);

        // Attach ingredients with quantities
        foreach ($validated['ingredients'] as $ingredientData) {
            $burger->ingredients()->attach($ingredientData['id'], [
                'quantity' => $ingredientData['quantity'],
            ]);
        }

        // Calculate and update total price
        $totalPrice = $burger->calculateTotalPrice();
        $burger->update(['total_price' => $totalPrice]);

        return redirect()->back()->with('success', 'Burger created successfully!');
    }

    public function update(Request $request, CustomBurger $burger)
    {
        $this->authorize('update', $burger);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|integer|min:1|max:10',
            'is_favorite' => 'boolean',
        ]);

        $burger->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_favorite' => $validated['is_favorite'] ?? false,
        ]);

        // Sync ingredients
        $syncData = [];
        foreach ($validated['ingredients'] as $ingredientData) {
            $syncData[$ingredientData['id']] = ['quantity' => $ingredientData['quantity']];
        }
        $burger->ingredients()->sync($syncData);

        // Recalculate price
        $totalPrice = $burger->calculateTotalPrice();
        $burger->update(['total_price' => $totalPrice]);

        return redirect()->back()->with('success', 'Burger updated successfully!');
    }

    public function destroy(CustomBurger $burger)
    {
        $this->authorize('delete', $burger);

        $burger->delete();

        return redirect()->back()->with('success', 'Burger deleted successfully!');
    }

    public function toggleFavorite(CustomBurger $burger)
    {
        $this->authorize('update', $burger);

        $burger->update([
            'is_favorite' => !$burger->is_favorite,
        ]);

        return redirect()->back();
    }
}