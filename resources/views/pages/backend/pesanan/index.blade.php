@extends('layouts.app')

@section('title', 'List Payment Details')

@section('main')
    <div class="main-content">
        <section class="section">


            <div class="section-header">
                <h1>Detail Transaksi</h1>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Data Detail Pembayaran</h4>
                    </div>

                    <div class="card-body table-responsive">
                        <div class="mb-3">
                            <form method="GET" action="{{ route('pesanan.index') }}" class="form-inline">
                                <label for="status" class="mr-2">Filter Status:</label>
                                <select name="status" id="status" class="form-control mr-2"
                                    onchange="this.form.submit()">
                                    <option value="">-- Semua --</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>
                                        Diproses</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                    </option>
                                    <option value="menunggu konfirmasi"
                                        {{ request('status') == 'menunggu konfirmasi' ? 'selected' : '' }}>Menunggu
                                        Konfirmasi</option>
                                </select>
                            </form>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Nama Barang</th>
                                    <th>Tanggal Dipesan</th>
                                    <th>Aksi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($allTransaksi as $index => $transaksi)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $transaksi->user->name ?? '-' }}</td>
                                        <td>
                                            <ul>
                                                @foreach ($transaksi->details as $detail)
                                                    <li>{{ $detail->item->nama ?? 'Barang tidak ditemukan' }} (Qty:
                                                        {{ $detail->qty }})</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $transaksi->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}
                                        </td>
                                        </td>
                                        <td>
                                            <a href="{{ route('pesanan.show', $transaksi->id) }}"
                                                class="btn btn-primary btn-sm">Lihat Detail</a>
                                        </td>
                                        <td class="text-white">
                                            @if ($transaksi->status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif ($transaksi->status === 'diproses')
                                                <span class="badge bg-primary">Diproses</span>
                                            @elseif ($transaksi->status === 'selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($transaksi->status) }}</span>
                                            @endif

                                            @if ($transaksi->status === 'menunggu konfirmasi')
                                                <form action="{{ route('pesanan.acc-batal', $transaksi->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-sm">ACC
                                                        Pembatalan</button>
                                                </form>

                                                <form action="{{ route('pesanan.tolak-batal', $transaksi->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-warning btn-sm">Tolak
                                                        Pembatalan</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Pagination DITARUH DI SINI --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $allTransaksi->appends(request()->query())->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
