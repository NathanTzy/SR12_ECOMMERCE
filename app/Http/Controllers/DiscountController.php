<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();
        return view('pages.backend.discount.index', compact('discounts'));
    }

    public function create()
    {
        $roles = Role::pluck('name');
        return view('pages.backend.discount.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $roles = Role::pluck('name')->toArray();

        $request->validate([
            'role'   => 'required|in:' . implode(',', $roles),
            'persen' => 'required|numeric|min:0|max:100',
        ]);

        Discount::create($request->all());

        return redirect()
            ->route('discount.index')
            ->with('success', 'Diskon berhasil ditambahkan');
    }

    public function edit(Discount $discount)
    {
        $roles = Role::pluck('name');
        return view('pages.backend.discount.show', compact('discount', 'roles'));
    }

    public function update(Request $request, Discount $discount)
    {
        $roles = Role::pluck('name')->toArray();

        $request->validate([
            'role'   => 'required|in:' . implode(',', $roles),
            'persen' => 'required|numeric|min:0|max:100',
        ]);

        $discount->update($request->all());

        return redirect()
            ->route('discount.index')
            ->with('success', 'Diskon berhasil diperbarui');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return back()->with('success', 'Diskon berhasil dihapus');
    }
}
