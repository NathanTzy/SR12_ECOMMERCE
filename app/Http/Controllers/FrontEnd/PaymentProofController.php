<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Payment;
use App\Models\PaymentProof;
use App\Models\PaymentProofDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{

    public function index(Request $request)
    {
        $query = PaymentProof::with(['details.item', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $allTransaksi = $query->latest()->paginate(5);

        $pending = PaymentProof::where('status', 'pending')->count();
        $diproses = PaymentProof::where('status', 'diproses')->count();
        $selesai = PaymentProof::where('status', 'selesai')->count();
        $menungguKonfirmasi = PaymentProof::where('status', 'menunggu konfirmasi')->count();

        return view('pages.backend.pesanan.index', compact(
            'allTransaksi',
            'pending',
            'diproses',
            'selesai',
            'menungguKonfirmasi'
        ));
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai'
        ]);

        $detail = PaymentProofDetail::findOrFail($id);
        $proof = $detail->paymentProof;

        if (!$proof) {
            return back()->with('error', 'Data pembayaran tidak ditemukan');
        }

        Log::info("Before update:", ['status' => $proof->status]);
        $proof->update(['status' => $request->status]);
        Log::info("After update:", ['status' => $proof->fresh()->status]);

        return back()->with('success', 'Status berhasil diperbarui');
    }

    public function show($id)
    {

        $payment = PaymentProof::with(['details.item', 'details.paymentProof'])->findOrFail($id);

        return view('pages.backend.pesanan.show', compact('payment'));
    }



    public function store(Request $request)
    {
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $path = $file->store('bukti_tf', 'public');
        } else {
            return back()->withErrors(['bukti_pembayaran' => 'File bukti transfer tidak ditemukan.']);
        }

        $validated = $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alamat' => 'required|array',
            'alamat.nama' => 'required|string|max:255',
            'alamat.alamat' => 'required|string',
            'alamat.kota' => 'required|string|max:255',
            'alamat.provinsi' => 'required|string|max:255',
            'alamat.telp' => 'required|string|max:20',
            'summary' => 'required|array',
            'summary.subtotal' => 'required|numeric',
            'summary.diskon_persen' => 'nullable|numeric|min:0|max:100',
            'summary.total' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.diskon' => 'nullable|numeric|min:0|max:100',
        ]);

        $alamat = $validated['alamat'];
        $provinsi = strtolower($alamat['provinsi']);
        $totalItem = collect($validated['items'])->sum('qty');

        // Ongkir = 0 jika kirim ke Sumatera Selatan
        $ongkir = (!str_contains($provinsi, 'sumatera selatan') && !str_contains($provinsi, 'sumsel'))
            ? $totalItem * 1000
            : 0;

        $totalWithOngkir = $validated['summary']['total'] + $ongkir;

        $paymentProof = PaymentProof::create([
            'user_id' => auth()->id(),
            'payment_id' => $validated['payment_id'],
            'bukti_tf' => $path,
            'nama_penerima' => $alamat['nama'],
            'alamat' => $alamat['alamat'],
            'kota' => $alamat['kota'],
            'provinsi' => $alamat['provinsi'],
            'telp' => $alamat['telp'],
            'subtotal' => $validated['summary']['subtotal'],
            'diskon_persen' => $validated['summary']['diskon_persen'] ?? 0,
            'total' => $totalWithOngkir,
            'status' => 'pending',
            'ongkir' => $ongkir,
        ]);

        foreach ($validated['items'] as $item) {
            PaymentProofDetail::create([
                'payment_proof_id' => $paymentProof->id,
                'item_id' => $item['item_id'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'diskon' => $item['diskon'] ?? 0,
            ]);
        }

        CartItem::where('user_id', auth()->id())->delete();

        return redirect()->route('cart.index')->with('success', 'Bukti pembayaran berhasil dikirim.');
    }


    public function accBatal($id)
    {
        $transaksi = PaymentProof::findOrFail($id);

        // Hapus bukti & detail
        $transaksi->details()->delete();
        $transaksi->delete();

        return back()->with('success', 'Pesanan berhasil dibatalkan dan dihapus.');
    }

    public function tolakBatal($id)
    {
        $transaksi = PaymentProof::findOrFail($id);
        $transaksi->status = 'pending';
        $transaksi->save();

        return back()->with('success', 'Permintaan pembatalan ditolak, pesanan kembali diproses.');
    }
}
