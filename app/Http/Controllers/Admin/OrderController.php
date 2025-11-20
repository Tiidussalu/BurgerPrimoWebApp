<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {

        $query = Order::with(['user', 'items', 'confirmedBy']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20);

        $stats = [
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'completed' => Order::where('status', 'completed')->count(),
        ];

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
            'stats' => $stats,
            'filters' => $request->only('status'),
        ]);
    }

    public function show(Order $order): Response
    {

        $order->load(['user', 'items', 'confirmedBy']);

        return Inertia::render('Admin/Orders/Show', [
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $updateData = ['status' => $validated['status']];

        // If confirming for the first time
        if ($validated['status'] === 'confirmed' && $order->status === 'pending') {
            $updateData['confirmed_at'] = now();
            $updateData['confirmed_by'] = auth()->id();
        }

        if (isset($validated['admin_notes'])) {
            $updateData['admin_notes'] = $validated['admin_notes'];
        }

        $order->update($updateData);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    public function confirm(Order $order)
    {

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be confirmed.');
        }

        $order->confirm(auth()->id());

        return redirect()->back()->with('success', 'Order confirmed successfully!');
    }

    public function bulkUpdateStatus(Request $request)
    {

        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:confirmed,preparing,ready,completed,cancelled',
        ]);

        Order::whereIn('id', $validated['order_ids'])
            ->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Orders updated successfully!');
    }

    public function reject(Request $request, Order $order)
    {

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $order->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'] ?? 'Tellimus lükati tagasi',
        ]);

        return redirect()->back()->with('success', 'Order rejected successfully!');
    }

    /**
     * Bulk delete completed orders
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        // Only allow deletion of completed orders for safety
        $deletedCount = Order::whereIn('id', $validated['order_ids'])
            ->where('status', 'completed')
            ->where('user_id', auth()->id()) // Ensure user can only delete their own orders
            ->delete();

        return redirect()->back()->with('success', "{$deletedCount} order(s) deleted successfully!");
    }
}