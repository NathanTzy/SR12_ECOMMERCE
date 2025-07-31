@extends('pages.frontend.parent')

@section('title', 'All Categories')

@section('content')
    <section class="section content-offset">
        <div class="container">

            @if (session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tombol Back --}}
            <div class="mb-4 mt-5 pt-5">
                <a href="{{ route('frontend.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Home
                </a>
            </div>

            {{-- Judul --}}
            <div class="row mb-3 mt-5">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>Track Pesananmu</h2>
                        <span>Jika tidak ada perubahan, mohon hubungi distributor</span>
                    </div>
                </div>
            </div>

            {{-- Filter Status --}}
            <div class="mb-4">
                <form method="GET" class="d-flex gap-2 align-items-center">
                    <select name="status" class="form-select w-auto">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <button type="submit" class="btn btn-outline-primary">Filter</button>
                </form>
            </div>

            {{-- Tabel Pesanan --}}
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Barang</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $index => $transaksi)
                        <tr>
                            <td>{{ $transaksis->firstItem() + $index }}</td>
                            <td>
                                <ul style="padding-left: 1rem;">
                                    @foreach ($transaksi->details as $detail)
                                        <li>
                                            {{ $detail->item->nama ?? 'Barang tidak ditemukan' }} (x{{ $detail->qty }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>Rp{{ number_format($transaksi->total, 0, ',', '.') }}</td>
                            <td>
                                @if ($transaksi->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($transaksi->status === 'diproses')
                                    <span class="badge bg-primary">Diproses</span>
                                @elseif ($transaksi->status === 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($transaksi->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('track-pesanan.show', $transaksi->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Lihat Detail
                                </a>

                                @if ($transaksi->status === 'pending')
                                    <form action="{{ route('track-pesanan.cancel', $transaksi->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan</button>
                                    </form>
                                @elseif ($transaksi->status === 'menunggu_konfirmasi')
                                    <span class="badge bg-secondary">Menunggu Konfirmasi Pembatalan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada transaksi.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $transaksis->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </section>
@endsection
