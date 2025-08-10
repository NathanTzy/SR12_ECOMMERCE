<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Tampilkan daftar metode pembayaran terbaru.
     */
    public function index()
    {
        $payments = Payment::latest()->get();
        return view('pages.backend.payment.index', compact('payments'));
    }

    /**
     * Tampilkan form tambah metode pembayaran baru.
     */
    public function create()
    {
        return view('pages.backend.payment.create');
    }

    /**
     * Simpan metode pembayaran baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bank'   => 'required|string|max:100',
            'atas_nama'   => 'required|string|max:100',
            'no_rekening' => 'required|regex:/^[0-9]+$/|digits_between:6,16',
        ]);

        Payment::create($request->only('nama_bank', 'atas_nama', 'no_rekening'));

        return redirect()
            ->route('payment.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit metode pembayaran.
     */
    public function edit(Payment $payment)
    {
        return view('pages.backend.payment.show', compact('payment'));
    }

    /**
     * Update data metode pembayaran.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'nama_bank'   => 'required|string|max:100',
            'atas_nama'   => 'required|string|max:100',
            'no_rekening' => 'required|regex:/^[0-9]+$/|digits_between:6,16',
        ]);

        $payment->update($request->only('nama_bank', 'atas_nama', 'no_rekening'));

        return redirect()
            ->route('payment.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui');
    }

    /**
     * Hapus metode pembayaran.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return back()->with('success', 'Metode pembayaran berhasil dihapus');
    }
}
