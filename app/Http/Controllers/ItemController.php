<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Discount;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Menampilkan daftar item dengan filter kategori dan pencarian.
     */
    public function index(Request $request)
    {
        $items = Item::with('category')
            ->when($request->filled('kategori'), fn ($q) => $q->where('category_id', $request->kategori))
            ->when($request->filled('search'), fn ($q) => $q->where('nama', 'like', '%' . $request->search . '%'))
            ->latest()
            ->paginate(5);

        $kategoriList = Category::all();
        $discounts = Discount::pluck('persen', 'role');

        return view('pages.backend.item.index', compact('items', 'kategoriList', 'discounts'));
    }

    /**
     * Tampilkan form untuk tambah item baru.
     */
    public function create()
    {
        $categories = Category::all();
        return view('pages.backend.item.create', compact('categories'));
    }

    /**
     * Simpan data item baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama'       => 'required|string|max:255',
            'img'        => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'harga'      => 'required|numeric|min:0',
            'kuantitas'  => 'required|integer|min:0',
            'deskripsi'  => 'required|string',
        ]);

        $path = $request->file('img')->store('items', 'public');

        Item::create([
            'category_id' => $request->category_id,
            'nama'        => $request->nama,
            'img'         => $path,
            'harga'       => $request->harga,
            'kuantitas'   => $request->kuantitas,
            'deskripsi'   => $request->deskripsi,
        ]);

        return redirect()
            ->route('item.index')
            ->with('success', 'Item berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit item.
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('pages.backend.Item.show', compact('item', 'categories'));
    }

    /**
     * Update data item yang sudah ada.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'nama'        => 'sometimes|string|max:255',
            'img'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'harga'       => 'sometimes|numeric|min:0',
            'kuantitas'   => 'sometimes|integer|min:0',
            'deskripsi'   => 'sometimes|string',
        ]);

        $data = $request->only(['category_id', 'nama', 'harga', 'kuantitas', 'deskripsi']);

        if ($request->hasFile('img')) {
            if ($item->img && Storage::disk('public')->exists($item->img)) {
                Storage::disk('public')->delete($item->img);
            }
            $data['img'] = $request->file('img')->store('items', 'public');
        }

        $item->update($data);

        return redirect()
            ->route('item.index')
            ->with('success', 'Item berhasil diperbarui');
    }

    /**
     * Hapus item beserta file gambarnya.
     */
    public function destroy(Item $item)
    {
        if ($item->img && Storage::disk('public')->exists($item->img)) {
            Storage::disk('public')->delete($item->img);
        }

        $item->delete();

        return back()->with('success', 'Item berhasil dihapus');
    }

    /**
     * Tampilkan riwayat transaksi item berdasarkan bulan dan tahun.
     */
    public function riwayat(Request $request, Item $item)
    {
        $month = $request->get('bulan');
        $year = $request->get('tahun');

        $detailsQuery = $item->paymentProofDetails()
            ->with(['payment.user'])
            ->whereHas('payment', function ($query) use ($month, $year) {
                if ($month) {
                    $query->whereMonth('created_at', $month);
                }
                if ($year) {
                    $query->whereYear('created_at', $year);
                }
            })
            ->latest();

        $details = $detailsQuery->get();

        $totalQty = $detailsQuery->sum('qty');
        $totalBayarKeseluruhan = $details->sum(fn ($detail) => ($detail->payment->total ?? 0) + ($detail->payment->ongkir ?? 0));

        $bulanList = collect(range(1, 12))->mapWithKeys(fn ($m) => [
            $m => Carbon::createFromDate(null, $m, 1)->format('F')
        ]);

        $tahunList = range(now()->year - 5, now()->year);

        return view('pages.backend.item.riwayat', compact(
            'item',
            'details',
            'bulanList',
            'tahunList',
            'month',
            'year',
            'totalQty',
            'totalBayarKeseluruhan'
        ));
    }
}
