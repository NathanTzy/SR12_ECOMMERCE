<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::latest()->get();
        return view('pages.backend.payment.index', compact('payments'));
    }

    public function create()
    {
        return view('pages.backend.payment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'atas_nama' => 'required|string|max:100',
            'no_rekening' => 'required|regex:/^[0-9]+$/|digits_between:6,16',
        ]);


        Payment::create([
            'nama_bank' => $request->nama_bank,
            'atas_nama' => $request->atas_nama,
            'no_rekening' => $request->no_rekening,
        ]);

        return redirect()->route('payment.index')->with('success', 'Metode pembayaran berhasil ditambahkan');
    }

    public function edit(Payment $payment)
    {
        return view('pages.backend.payment.show', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'atas_nama' => 'required|string|max:100',
            'no_rekening' => 'required|regex:/^[0-9]+$/|digits_between:6,16',
        ]);

        $data = $request->only(['nama_bank', 'atas_nama', 'no_rekening']);

       

        $payment->update($data);

        return redirect()->route('payment.index')->with('success', 'Metode pembayaran berhasil diperbarui');
    }

    public function destroy(Payment $payment)
    {

        $payment->delete();

        return back()->with('success', 'Metode pembayaran berhasil dihapus');
    }
}
