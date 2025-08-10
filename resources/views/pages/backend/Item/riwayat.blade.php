@extends('layouts.app')

@section('title', 'Riwayat Pembelian ' . $item->nama)

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Riwayat Pembelian - {{ $item->nama }}</h1>
            </div>

            <div class="section-body">

                {{-- Filter Bulan & Tahun --}}
                <form method="GET" class="mb-4 d-flex flex-wrap align-items-center">
                    <select name="bulan" class="form-control mr-3" style="max-width: 150px;">
                        <option value="">-- Pilih Bulan --</option>
                        @foreach ($bulanList as $num => $nama)
                            <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $nama }}
                            </option>
                        @endforeach
                    </select>

                    <select name="tahun" class="form-control mr-2" style="max-width: 150px;">
                        <option value="">-- Pilih Tahun --</option>
                        @foreach ($tahunList as $th)
                            <option value="{{ $th }}" {{ $year == $th ? 'selected' : '' }}>{{ $th }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary ">Filter</button>

                    @if ($month || $year)
                        <a href="{{ route('item.riwayat', $item->id) }}" class="btn btn-secondary ml-2">Reset</a>
                    @endif
                </form>
                <div class="mb-3">
                    @if ($month || $year)
                        <h5>Total pembelian pada
                            {{ $month ? \Carbon\Carbon::create()->month((int) $month)->format('F') : '' }}
                            {{ $year ?? '' }}
                            adalah: <strong>{{ $totalQty }}</strong> pcs
                        </h5>
                        <h5>Total penghasilan:
                            <strong>
                                Rp{{ number_format($details->sum(fn($d) => $d->qty * (($d->payment->total ?? 0) + ($d->payment->ongkir ?? 0))), 0, ',', '.') }}
                            </strong>
                        </h5>
                    @else
                        <h5>Total pembelian selama ini: <strong>{{ $totalQty }}</strong> pcs</h5>
                        <h5>Total penghasilan:
                            <strong>
                                Rp{{ number_format(
                                    $details->sum(function ($d) {
                                        $harga = $d->harga ?? ($d->barang->harga ?? 0);
                                        $qty = $d->qty;
                                        $subtotal = $harga * $qty;
                                        $diskonPersen = $d->payment->diskon_persen ?? 0;
                                        $diskon = ($subtotal * $diskonPersen) / 100;
                                        $ongkir = $d->payment->ongkir ?? 0;
                                        return $subtotal - $diskon + $ongkir;
                                    }),
                                    0,
                                    ',',
                                    '.',
                                ) }}
                            </strong>
                        </h5>

                        </h5>
                    @endif
                </div>


                <div class="card">
                    <div class="card-header">
                        <h4>Detail Pembelian</h4>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-striped table-bordered text-center align-middle">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Diskon</th>
                                    <th scope="col">Biaya diluar sumsel</th>
                                    <th scope="col">Total Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchases as $detail)
                                    @php
                                        // Data dasar
                                        $qty = $detail->qty;
                                        $hargaBarang = $detail->harga ?? ($detail->barang->harga ?? 0);
                                        $diskonPersen = $detail->payment->diskon_persen ?? 0;
                                        $ongkir = $detail->payment->ongkir ?? 0;

                                        // Hitung diskon
                                        $hargaDiskon = $hargaBarang * (1 - $diskonPersen / 100);

                                        // Subtotal & total akhir
                                        $subtotal = $hargaDiskon * $qty;
                                        $totalAkhir = $subtotal + $ongkir;
                                    @endphp

                                    <tr>
                                        <td>{{ $detail->payment->user->name ?? '-' }}</td>
                                        <td><span class="badge badge-primary">{{ $qty }}</span></td>
                                        <td>Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                                        <td><span class="text-danger font-weight-bold">{{ $diskonPersen }}%</span></td>
                                        <td>Rp{{ number_format($ongkir, 0, ',', '.') }}</td>
                                        <td class="fw-bold text-success">Rp{{ number_format($totalAkhir, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-muted">Tidak ada data riwayat pembelian.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>

                    </div>
                </div>
                <a href="{{ route('item.index') }}" class="btn btn-outline-primary mt-4">
                    <i class="fa fa-arrow-left"></i> Kembali ke List Item
                </a>
            </div>
        </section>
    </div>
@endsection
