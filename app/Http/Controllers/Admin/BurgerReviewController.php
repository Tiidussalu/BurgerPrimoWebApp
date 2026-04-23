<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomBurger;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BurgerReviewController extends Controller
{
    // Eraldi leht burger ülevaatuseks
    public function index()
    {
        $query = CustomBurger::with(['user', 'ingredients'])
            ->orderBy('created_at', 'asc');

        $map = fn($burger) => [
            'id'          => $burger->id,
            'name'        => $burger->name,
            'total_price' => (float) $burger->total_price,
            'created_at'  => $burger->created_at->format('d.m.Y H:i'),
            'status'      => $burger->status,
            'admin_note'  => $burger->admin_note,
            'user'        => [
                'name'  => $burger->user->name,
                'email' => $burger->user->email,
            ],
            'ingredients' => $burger->ingredients->map(fn($i) => [
                'name'     => $i->name,
                'category' => $i->category,
                'quantity' => $i->pivot->quantity,
                'price'    => (float) $i->price,
            ]),
        ];

        return Inertia::render('Admin/BurgerReview/Index', [
            'pendingBurgers'  => $query->clone()->where('status', 'pending')->get()->map($map)->values(),
            'approvedBurgers' => $query->clone()->where('status', 'approved')->get()->map($map)->values(),
            'rejectedBurgers' => $query->clone()->where('status', 'rejected')->get()->map($map)->values(),
        ]);
    }

    public function approve(CustomBurger $burger)
    {
        $burger->update(['status' => CustomBurger::STATUS_APPROVED, 'admin_note' => null]);
        return redirect()->back()->with('success', "Burger \"{$burger->name}\" kinnitatud!");
    }

    public function reject(Request $request, CustomBurger $burger)
    {
        $validated = $request->validate(['admin_note' => 'required|string|max:500']);
        $burger->update(['status' => CustomBurger::STATUS_REJECTED, 'admin_note' => $validated['admin_note']]);
        return redirect()->back()->with('success', "Burger \"{$burger->name}\" tagasi lükatud.");
    }
}