<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\PaymentProof;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TrackPesananController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->input('status');

        $query = PaymentProof::with('details.item')
            ->where('user_id', $user->id);

        if ($status) {
            $query->where('status', $status);
        }

        $transaksis = $query->latest()->paginate(6)->withQueryString();

        return view('pages.frontend.track-pesanan', compact('transaksis', 'status'));
    }
    public function show($id)
    {
        $user = Auth::user();

        $payment = PaymentProof::with('details.item')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('pages.frontend.track-pesanan-show', compact('payment'));
    }
    public function cancel($id)
    {
        $transaksi = PaymentProof::findOrFail($id);
        $transaksi->status = 'menunggu konfirmasi';
        $transaksi->save();

        return redirect()->back()->with('success', 'Permintaan pembatalan telah dikirim.');
    }
}
