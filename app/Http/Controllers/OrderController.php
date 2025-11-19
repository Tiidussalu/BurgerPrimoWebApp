<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CustomBurger;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        $orders = auth()->user()
            ->orders()
            ->with('items')
            ->latest()
            ->get();

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'burgers' => 'required|array|min:1',
            'burgers.*.burger_id' => 'required|exists:custom_burgers,id',
            'burgers.*.quantity' => 'required|integer|min:1|max:10',
            'customer_notes' => 'nullable|string|max:500',
        ]);

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => Order::generateOrderNumber(),
            'total_amount' => 0,
            'status' => 'pending',
            'customer_notes' => $validated['customer_notes'] ?? null,
        ]);

        $totalAmount = 0;

        foreach ($validated['burgers'] as $burgerData) {
            $burger = CustomBurger::with('ingredients')->findOrFail($burgerData['burger_id']);
            
            if ($burger->user_id !== auth()->id()) {
                abort(403, 'Unauthorized');
            }

            $price = $burger->total_price;
            $quantity = $burgerData['quantity'];
            $itemTotal = $price * $quantity;
            $totalAmount += $itemTotal;

            $ingredientsData = $burger->ingredients->map(function ($ingredient) {
                return [
                    'name' => $ingredient->name,
                    'category' => $ingredient->category,
                    'quantity' => $ingredient->pivot->quantity,
                    'price' => $ingredient->price,
                ];
            })->toArray();

            $order->items()->create([
                'custom_burger_id' => $burger->id,
                'burger_name' => $burger->name,
                'ingredients' => $ingredientsData,
                'price' => $price,
                'quantity' => $quantity,
            ]);
        }

        $order->update(['total_amount' => $totalAmount]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order placed successfully! Order number: ' . $order->order_number);
    }

    public function show(Order $order): Response
    {
        // Check if user owns this order or is admin
        if ($order->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $order->load(['items', 'confirmedBy']);

        return Inertia::render('Orders/Show', [
            'order' => $order,
        ]);
    }

    public function cancel(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Cannot cancel order at this stage.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Order cancelled successfully.');
    }

    public function destroy(Order $order)
{
    // Check if user owns this order
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    // Only allow deletion of rejected or cancelled orders
    if (!in_array($order->status, ['rejected', 'cancelled'])) {
        return redirect()->back()->with('error', 'Only rejected or cancelled orders can be removed.');
    }

    $order->delete();

    return redirect()->back()->with('success', 'Order removed from list.');
}

}
