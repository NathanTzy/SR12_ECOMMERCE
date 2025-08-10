<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\PaymentProof;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TrackPesananController extends Controller
{
    /**
     * Menampilkan daftar pesanan user yang sedang login
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->input('status');

        // Query transaksi berdasarkan user
        $query = PaymentProof::with('details.item')
            ->where('user_id', $user->id);

        // Filter berdasarkan status jika ada
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // Ambil data terbaru dengan pagination
        $transaksis = $query->latest()
            ->paginate(6)
            ->withQueryString();

        return view('pages.frontend.track-pesanan', compact('transaksis', 'status'));
    }

    /**
     * Menampilkan detail pesanan tertentu
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = Auth::user();

        $payment = PaymentProof::with('details.item')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('pages.frontend.track-pesanan-show', compact('payment'));
    }

    /**
     * Mengajukan pembatalan pesanan
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        $transaksi = PaymentProof::findOrFail($id);
        $transaksi->status = 'menunggu konfirmasi';
        $transaksi->save();

        return redirect()
            ->back()
            ->with('success', 'Permintaan pembatalan telah dikirim.');
    }
}
