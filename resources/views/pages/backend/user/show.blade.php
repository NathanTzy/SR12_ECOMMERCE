@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Pengguna</h1>
            </div>

            <div class="section-body">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Informasi Akun</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered mb-0">
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>{{ $user->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Bergabung</th>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <th>Tempat Lahir</th>
                                <td>{{ $user->tempat_lahir }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td>{{ $user->tanggal_lahir }}</td>
                            </tr>
                            <tr>
                                <th>Kecamatan</th>
                                <td>{{ $user->kecamatan }}</td>
                            </tr>
                            <tr>
                                <th>Kota</th>
                                <td>{{ $user->kota }}</td>
                            </tr>
                            <tr>
                                <th>Provinsi</th>
                                <td>{{ $user->provinsi }}</td>
                            </tr>
                            <tr>
                                <th>No WhatsApp</th>
                                <td>{{ $user->no_wa }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Username</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{ $user->getRoleNames()->first() }}</td>
                            </tr>
                        </table>

                        <h4 class="mt-5 mb-3 ml-3">Riwayat Pembelian</h4>
                        @php
                            $totalUangMasuk = $purchases->sum(function ($purchase) {
                                return $purchase->details->sum(function ($d) {
                                    $hargaBarang = $d->harga ?? ($d->item->harga ?? 0);
                                    $diskonPersen = $d->payment->diskon_persen ?? 0;
                                    $ongkir = $d->payment->ongkir ?? 0;

                                    $hargaDiskon = $hargaBarang * (1 - $diskonPersen / 100);
                                    $subtotal = $hargaDiskon * $d->qty;

                                    return $subtotal + $ongkir;
                                });
                            });
                        @endphp
                        <form method="GET" action="" class="form-inline mb-4 ml-3">
                            <select name="tahun" class="form-control mr-2">
                                <option value="">Pilih Tahun</option>
                                @foreach (range(date('Y'), 2020) as $y)
                                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endforeach
                            </select>

                            <select name="bulan" class="form-control mr-2">
                                <option value="">Pilih Bulan</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>

                        <h4 class="mb-5 ml-3">
                            Total uang masuk :
                            <span class="badge badge-primary">Rp {{ number_format($totalUangMasuk, 0, ',', '.') }}</span>
                        </h4>

                        @if ($purchases->isEmpty())
                            <p class="ml-3">Belum ada pembelian.</p>
                        @else
                            <table class="table mb-5">
                                <thead>
                                    <tr>
                                        <th>Waktu pemesanan</th>
                                        <th>Item</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $purchase)
                                        <tr>
                                            <td>{{ $purchase->created_at->format('d-m-Y H:i') }}</td>
                                            <td>
                                                <ul>
                                                    @foreach ($purchase->details as $detail)
                                                        <li>{{ $detail->item->nama }} (x{{ $detail->qty }})</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>Rp
                                                {{ number_format(
                                                    $purchase->details->sum(function ($d) {
                                                        $hargaBarang = $d->harga ?? ($d->item->harga ?? 0);
                                                        $diskonPersen = $d->payment->diskon_persen ?? 0;
                                                        $ongkir = $d->payment->ongkir ?? 0;
                                                
                                                        // Hitung harga setelah diskon
                                                        $hargaDiskon = $hargaBarang * (1 - $diskonPersen / 100);
                                                
                                                        // Hitung subtotal per item
                                                        $subtotal = $hargaDiskon * $d->qty;
                                                
                                                        // Tambah ongkir
                                                        return $subtotal + $ongkir;
                                                    }),
                                                    0,
                                                    ',',
                                                    '.',
                                                ) }}
                                            </td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                    </div>
                    <button class="btn btn-success mb-3 mx-5" onclick="copyToClipboard()">
                        <i class="fas fa-copy"></i> Salin ke Spreadsheet
                    </button>
                </div>

                <a href="{{ route('users.index') }}" class="btn btn-secondary mt-4">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </section>
    </div>
@endsection

<script>
    function copyToClipboard() {
        let table = document.querySelector(".table");
        let rows = table.querySelectorAll("tr");
        let output = "";

        rows.forEach((row, index) => {
            let td = row.querySelectorAll("td");
            if (td.length) {
                output += td[0].innerText.trim();
                if (index < rows.length - 1) output += "\t"; // pakai tab antar kolom
            }
        });

        let textarea = document.createElement("textarea");
        textarea.value = output;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
    }
</script>
