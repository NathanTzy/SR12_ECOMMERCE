@extends('layouts.app')

@section('title', 'Detail Pemesanan')

@section('main')
    <div class="main-content">
        <section class="section">

            <div class="section-header">
                <h1>Detail Pemesanan</h1>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="section-body">

                {{-- Detail Alamat --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Informasi Penerima</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama Penerima:</strong> {{ $payment->nama_penerima }}</p>
                        <p><strong>Alamat:</strong> {{ $payment->alamat }}, {{ $payment->kota }}, {{ $payment->provinsi }}
                        </p>
                        <p><strong>No Telp:</strong> {{ $payment->telp }}</p>
                    </div>
                </div>

                {{-- Tabel Barang yang Dipesan --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Daftar Barang Dipesan</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Harga Satuan</th>
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
                        <button class="btn btn-sm btn-primary mb-2" onclick="copyDataBarang()"> <i class="fas fa-copy"></i> Salin rincian pembelian</button>

                    </div>
                </div>

                {{-- Rincian Harga --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Rincian Harga</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Subtotal:</strong> Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                        <p><strong>Diskon:</strong> {{ $payment->diskon_persen ?? 0 }}%</p>
                        <p><strong>Nominal Diskon:</strong>
                            Rp{{ number_format(($subtotal * ($payment->diskon_persen ?? 0)) / 100, 0, ',', '.') }}</p>
                        <p><strong>Harga diluar sumsel :</strong>
                            + Rp{{ number_format($payment->ongkir) }}</p>
                        <p><strong>Total Setelah Diskon:</strong>
                            <span class="badge badge-success">Rp{{ number_format($payment->total, 0, ',', '.') }}</span>
                        </p>
                    </div>
                    <button class="btn btn-primary my-3 mx-3" onclick="copyRincianHarga()">
                        <i class="fas fa-copy"></i> Salin Rincian
                    </button>

                </div>


                {{-- Bukti Transfer --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Bukti Transfer</h4>
                    </div>
                    <div class="card-body text-center">
                        @if ($payment->bukti_tf)
                            <img id="bukti-transfer" src="{{ asset('storage/' . $payment->bukti_tf) }}" width="300"
                                style="cursor: zoom-in; border-radius: 8px; transition: 0.3s;">
                        @else
                            <p class="text-muted">Belum ada bukti transfer diunggah.</p>
                        @endif
                    </div>
                </div>

                {{-- Status Transaksi --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Status Transaksi</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST"
                            action="{{ route('paymentDetail.updateStatus', $payment->details->first()->id) }}">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-control" onchange="this.form.submit()">
                                <option value="pending" {{ $payment->status === 'pending' ? 'selected' : '' }}>pending
                                </option>
                                <option value="diproses" {{ $payment->status === 'diproses' ? 'selected' : '' }}>diproses
                                </option>
                                <option value="selesai" {{ $payment->status === 'selesai' ? 'selected' : '' }}>selesai
                                </option>
                            </select>
                        </form>
                    </div>
                </div>

            </div>

            <div class="text-left mt-3">
                <a href="{{ route('pesanan.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </section>
    </div>

    {{-- Zoom script --}}
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

        <script>
            function copyRincianHarga() {
                const subtotal = "{{ str_replace('.', '', number_format($subtotal, 0, ',', '.')) }}";
                const diskonPersen = "{{ $payment->diskon_persen ?? 0 }}%";
                const nominalDiskon =
                    "{{ str_replace('.', '', number_format(($subtotal * ($payment->diskon_persen ?? 0)) / 100, 0, ',', '.')) }}";
                const ongkir = "{{ str_replace('.', '', number_format($payment->ongkir)) }}";
                const total = "{{ str_replace('.', '', number_format($payment->total, 0, ',', '.')) }}";

                const text = `${subtotal}\t${diskonPersen}\t${nominalDiskon}\t${ongkir}\t${total}`;

                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }
        </script>

        <script>
            function copyDataBarang() {
                let rows = [];

                @foreach ($payment->details as $detail)
                    const nama = `{{ $detail->item->nama ?? 'Barang tidak ditemukan' }}`;
                    const qty = `{{ $detail->qty }}`;
                    const harga = `{{ str_replace('.', '', number_format($detail->item->harga ?? 0, 0, ',', '.')) }}`;
                    const subtotal =
                        `{{ str_replace('.', '', number_format(($detail->item->harga ?? 0) * $detail->qty, 0, ',', '.')) }}`;
                    rows.push(`${nama}\t${qty}\t${harga}\t${subtotal}`);
                @endforeach

                const text = rows.join('\n');
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }
        </script>
    @endif
@endsection
