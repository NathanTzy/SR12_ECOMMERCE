<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\CartItem;
use App\Models\Discount;
use App\Models\Item;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Menambahkan item ke keranjang.
     * Jika item sudah ada, maka akan menambah jumlahnya.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'qty'     => 'required|integer|min:1',
        ]);

        // Cek apakah item sudah ada di keranjang
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('item_id', $request->item_id)
            ->first();

        if ($cartItem) {
            // Jika sudah ada, tambah jumlah qty
            $cartItem->increment('qty', $request->qty);
        } else {
            // Ambil data item
            $item = Item::findOrFail($request->item_id);

            // Simpan ke keranjang
            CartItem::create([
                'user_id'           => auth()->id(),
                'item_id'           => $item->id,
                'qty'               => $request->qty,
                'harga_saat_itulah' => $item->harga,
            ]);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Produk ditambahkan ke keranjang.');
    }

    /**
     * Memperbarui jumlah item di keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        // Ambil item keranjang berdasarkan user
        $cartItem = CartItem::where('user_id', Auth::id())
            ->findOrFail($id);

        // Batasi qty sesuai stok
        $maxQty = $cartItem->item->kuantitas;
        $newQty = min($request->qty, $maxQty);

        // Update qty
        $cartItem->update(['qty' => $newQty]);

        return back()->with('success', 'Jumlah item berhasil diperbarui.');
    }

    /**
     * Menampilkan halaman keranjang.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user      = Auth::user();
        $role      = $user->getRoleNames()->first();
        $cartItems = CartItem::with('item')
            ->where('user_id', $user->id)
            ->get();

        // Ambil diskon berdasarkan role
        $diskonPersen = Discount::where('role', $role)->value('persen') ?? 0;

        // Hitung subtotal
        $subtotal = $cartItems->sum(fn($cart) => $cart->harga_saat_itulah * $cart->qty);

        // Hitung potongan dan total akhir
        $potongan   = $subtotal * ($diskonPersen / 100);
        $totalAkhir = $subtotal - $potongan;

        // Data alamat & metode pembayaran
        $alamatList = Alamat::where('user_id', $user->id)->get();
        $payments   = Payment::latest()->get();

        return view('pages.frontend.cart', compact(
            'cartItems',
            'diskonPersen',
            'subtotal',
            'potongan',
            'totalAkhir',
            'alamatList',
            'payments'
        ));
    }

    /**
     * Menghapus item dari keranjang.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->findOrFail($id);

        $cartItem->delete();

        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
