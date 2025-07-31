@extends('pages.frontend.parent')

@section('title', 'Detail Pesanan')

@section('content')
    <section class="section content-offset">
        <div class="container">

            {{-- Tombol Kembali --}}
            <div class="mb-4 mt-5 pt-5">
                <a href="{{ route('track-pesanan.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Kembali ke Pesanan
                </a>
            </div>

            {{-- Judul --}}
            <div class="row mb-3 mt-3">
                <div class="col-lg-12">
                    <h2>Detail Pesanan</h2>
                </div>
            </div>

            {{-- Info Penerima --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informasi Penerima</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong> {{ $payment->nama_penerima }}</p>
                    <p><strong>Alamat:</strong> {{ $payment->alamat }}, {{ $payment->kota }}, {{ $payment->provinsi }}</p>
                    <p><strong>No. Telepon:</strong> {{ $payment->telp }}</p>
                </div>
            </div>

            {{-- Barang Dipesan --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Barang yang Dipesan</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subtotal = 0; @endphp
                            @foreach ($payment->details as $detail)
                                @php
                                    $harga = $detail->item->harga ?? 0;
                                    $qty = $detail->qty;
                                    $subtotal += $harga * $qty;
                                @endphp
                                <tr>
                                    <td>{{ $detail->item->nama ?? 'Barang tidak ditemukan' }}</td>
                                    <td>{{ $qty }}</td>
                                    <td>Rp{{ number_format($harga, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($harga * $qty, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Rincian Harga --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Rincian Harga</h5>
                </div>
                <div class="card-body">
                    <p><strong>Subtotal:</strong> Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                    <p><strong>Diskon:</strong> {{ $payment->diskon_persen ?? 0 }}%</p>
                    <p><strong>Potongan Diskon:</strong>
                        Rp{{ number_format(($subtotal * ($payment->diskon_persen ?? 0)) / 100, 0, ',', '.') }}</p>
                    <p><strong>Biaya diluar Sumsel:</strong> Rp{{ number_format($payment->ongkir ?? 0, 0, ',', '.') }}</p>
                    <hr>
                    <p><strong>Total Bayar:</strong>
                        <span class="badge bg-success">Rp{{ number_format($payment->total, 0, ',', '.') }}</span>
                    </p>
                </div>

            </div>

            {{-- Status Transaksi --}}
            <div class="card mb-5">
                <div class="card-header">
                    <h5>Status Transaksi</h5>
                </div>
                <div class="card-body">
                    @if ($payment->status === 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif ($payment->status === 'diproses')
                        <span class="badge bg-primary">Diproses</span>
                    @elseif ($payment->status === 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Zoom Script --}}
    @if ($payment->bukti_tf)
        <style>
            .zoom-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color: rgba(0, 0, 0, 0.7);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }

            .zoom-overlay img {
                max-width: 90%;
                max-height: 90%;
                border-radius: 12px;
                box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
            }
        </style>
        <script>
            document.getElementById('bukti-transfer').addEventListener('click', () => {
                const overlay = document.createElement('div');
                overlay.classList.add('zoom-overlay');
                overlay.innerHTML = `<img src="{{ asset('storage/' . $payment->bukti_tf) }}" alt="Zoomed Bukti">`;
                overlay.addEventListener('click', () => overlay.remove());
                document.body.appendChild(overlay);
            });
        </script>
    @endif
@endsection
