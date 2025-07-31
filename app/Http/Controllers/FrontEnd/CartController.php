<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Item;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('item_id', $request->item_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('qty', $request->qty);
        } else {
            $hargaSekarang = \App\Models\Item::where('id', $request->item_id)->value('harga');

            $item = Item::findOrFail($request->item_id);

            CartItem::create([
                'user_id' => auth()->id(),
                'item_id' => $item->id,
                'qty' => $request->qty,
                'harga_saat_itulah' => $item->harga,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $cartItem = \App\Models\CartItem::where('user_id', Auth::id())->findOrFail($id);

        $maxQty = $cartItem->item->kuantitas;
        $newQty = min($request->qty, $maxQty);

        $cartItem->update([
            'qty' => $newQty
        ]);

        return back()->with('success', 'Jumlah item berhasil diperbarui.');
    }


    public function index()
    {
        $user = Auth::user();
        $cartItems = CartItem::with('item')->where('user_id', $user->id)->get();
        $role = $user->getRoleNames()->first();

        $diskon = \App\Models\Discount::where('role', $role)->first();
        $diskonPersen = $diskon?->persen ?? 0;

        $subtotal = $cartItems->sum(function ($cart) {
            return $cart->harga_saat_itulah * $cart->qty;
        });

        $potongan = $subtotal * ($diskonPersen / 100);
        $totalAkhir = $subtotal - $potongan;

        $alamatList = Alamat::where('user_id', $user->id)->get();
        $payments = Payment::latest()->get();

        return view('pages.frontend.cart', compact(
            'cartItems',
            'diskonPersen',
            'subtotal',
            'potongan',
            'totalAkhir',
            'alamatList',
            'payments',
        ));
    }




    public function destroy($id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
