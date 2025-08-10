@extends('pages.frontend.parent')

@section('title', 'Keranjang Belanja')

@section('content')
    <section class="section content-offset" id="cart">
        <div class="container mt-5 pt-5">
            <h3 class="mb-4 fw-bold text-center">Keranjang Belanja</h3>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-4 mx-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-4 mx-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            {{-- Tabel Produk --}}
            <div class="table-responsive mb-5">
                <table class="table table-bordered align-middle shadow-sm">
                    <thead class="table-dark text-white">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subtotal = 0;
                        @endphp


                        @forelse ($cartItems as $cart)
                            @php
                                $harga = $cart->item->harga;
                                $hargaDiskon = $harga - ($harga * $diskonPersen) / 100;
                                $sub = $hargaDiskon * $cart->qty;
                                $subtotal += $sub;
                            @endphp

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/' . $cart->item->img) }}" alt="img" width="60"
                                            class="rounded border">
                                        <div>
                                            <strong>{{ $cart->item->nama }}</strong><br>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                                <td>
                                    @if ($diskonPersen > 0)
                                        <span class="text-success">{{ $diskonPersen }}%</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td style="max-width: 140px;">
                                    <form action="{{ route('cart.update', $cart->id) }}" method="POST"
                                        class="d-flex align-items-center">
                                        @csrf @method('PUT')
                                        <input type="number" name="qty"
                                            class="form-control form-control-sm text-center" value="{{ $cart->qty }}"
                                            min="1" max="{{ $cart->item->kuantitas }}" style="width: 80px;">
                                        <button class="btn btn-sm btn-outline-secondary ms-2">Ubah</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('cart.destroy', $cart->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus item ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Keranjang belanja kosong.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            {{-- Ringkasan --}}
            <div class="row">
                {{-- informasi penerima --}}
                <div class="col-md-6 mb-4">
                    <div class="p-4 border rounded bg-light shadow-sm">
                        <h5 class="fw-semibold mb-3">Informasi Pengiriman</h5>

                        @if ($cartItems->isEmpty())
                            <div class="alert alert-warning">Belum ada barang di keranjang.</div>
                        @else
                            @if ($alamatList->isEmpty())
                                <a class="btn btn-dark" href="{{ route('alamat.index') }}">
                                    Buat alamat terlebih dahulu
                                </a>
                            @else
                                <select class="form-select" id="alamatSelect">
                                    <option value="" disabled selected>-- Pilih Alamat --</option>
                                    @foreach ($alamatList as $i => $alamat)
                                        <option value="{{ $alamat->id }}" data-nama="{{ $alamat->nama_penerima }}"
                                            data-alamat="{{ $alamat->alamat_lengkap }}" data-kota="{{ $alamat->kota }}"
                                            data-provinsi="{{ $alamat->provinsi }}" data-telp="{{ $alamat->no_telp }}">
                                            Alamat {{ $i + 1 }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                            <div id="alamatDetail" class="d-none mt-3">
                                <div class="card shadow-sm border-0 bg-white">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-1" id="namaPenerimaCard"></h6>
                                        <p class="mb-1" id="alamatLengkapCard"></p>
                                        <p class="mb-1 text-muted" id="kotaProvinsiCard"></p>
                                        <p class="mb-0 text-muted" id="teleponCard"></p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- total harga --}}
                <div class="col-md-6">
                    <div class="p-4 border rounded bg-white shadow-sm">
                        <h5 class="fw-semibold mb-3">Ringkasan Belanja</h5>

                        @php
                            $subtotal = 0;
                            $ongkirPerItem = 0;

                            // Provinsi bebas ongkir (Pulau Jawa)
                            $provinsiBebasOngkir = [
                                'banten',
                                'dki jakarta',
                                'jakarta',
                                'jawa barat',
                                'jawa tengah',
                                'di yogyakarta',
                                'yogyakarta',
                                'jawa timur',
                            ];

                            foreach ($cartItems as $item) {
                                $hargaAsli = $item->item->harga;
                                $qty = $item->qty;
                                $subtotal += $hargaAsli * $qty;

                                if (isset($alamatUser->provinsi)) {
                                    $prov = strtolower($alamatUser->provinsi);
                                    // Jika provinsi tidak ada di list bebas ongkir → kena ongkir 1000 per item
                                    if (!in_array($prov, $provinsiBebasOngkir)) {
                                        $ongkirPerItem += 1000 * $qty;
                                    }
                                }
                            }

                            $diskonPersen = $diskonPersen ?? 0;
                            $totalDiskon = ($diskonPersen / 100) * $subtotal;
                            $totalSetelahDiskon = $subtotal - $totalDiskon;
                            $totalAkhir = $totalSetelahDiskon + $ongkirPerItem;
                        @endphp


                        <ul class="list-group mb-3">
                            <input type="hidden" id="ongkirInput" name="ongkir" value="{{ $ongkirPerItem }}">

                            <li class="list-group-item d-flex justify-content-between">
                                <span>Subtotal</span>
                                <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </li>

                            @if ($diskonPersen > 0)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="text-danger">Diskon ({{ $diskonPersen }}%)</span>
                                    <span class="text-danger">- Rp {{ number_format($totalDiskon, 0, ',', '.') }}</span>
                                </li>
                            @endif

                            <li class="list-group-item d-flex justify-content-between">
                                <span>Biaya diluar Sumsel</span>
                                <span id="ongkirDisplay">Rp {{ number_format($ongkirPerItem, 0, ',', '.') }}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total</span>
                                <strong class="text-success" id="totalFinalText">
                                    Rp {{ number_format($totalAkhir, 0, ',', '.') }}
                                </strong>
                            </li>
                        </ul>
                        <small class="text-muted mb-3 d-block">
                            * Setiap pembelian dengan alamat diluar sumsel/provinsi sumatera selatan
                            akan ditambahkan biaya sebesar Rp 1.000,- per item/product
                        </small>

                        <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            Lanjut ke Checkout
                        </button>

                        @include('pages.frontend.components.modal-payment', ['payments' => $payments])
                    </div>
                </div>

            </div>

        </div>
    </section>

    @push('scripts')
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const select = document.getElementById('alamatSelect');
                    const detailCard = document.getElementById('alamatDetail');
                    const namaPenerimaCard = document.getElementById('namaPenerimaCard');
                    const alamatLengkapCard = document.getElementById('alamatLengkapCard');
                    const kotaProvinsiCard = document.getElementById('kotaProvinsiCard');
                    const teleponCard = document.getElementById('teleponCard');
                    const ongkirInput = document.getElementById('ongkirInput');
                    const ongkirDisplay = document.getElementById('ongkirDisplay');
                    const totalFinalText = document.getElementById('totalFinalText');

                    const subtotal = {{ $subtotal }};
                    const totalDiskon = {{ $totalDiskon }};
                    const baseTotal = subtotal - totalDiskon;

                    select.addEventListener('change', function() {
                        const selected = this.options[this.selectedIndex];
                        const nama = selected.dataset.nama;
                        const alamat = selected.dataset.alamat;
                        const kota = selected.dataset.kota?.toLowerCase().trim() || '';
                        const provinsi = selected.dataset.provinsi;
                        const telp = selected.dataset.telp;

                        namaPenerimaCard.textContent = nama;
                        alamatLengkapCard.textContent = alamat;
                        kotaProvinsiCard.textContent = `${kota}, ${provinsi}`;
                        teleponCard.textContent = `Telp: ${telp}`;
                        detailCard.classList.remove('d-none');

                        let totalQty = 0;
                        @foreach ($cartItems as $item)
                            totalQty += {{ $item->qty }};
                        @endforeach

                        // Provinsi bebas ongkir
                        const provinsiBebasOngkir = [
                            'banten',
                            'dki jakarta',
                            'jakarta',
                            'jawa barat',
                            'jawa tengah',
                            'di yogyakarta',
                            'yogyakarta',
                            'jawa timur'
                        ];

                        let ongkir = 0;
                        const prov = provinsi?.toLowerCase().trim() || '';
                        if (!provinsiBebasOngkir.some(p => prov.includes(p))) {
                            ongkir = 1000 * totalQty;
                        }



                        ongkirInput.value = ongkir;
                        ongkirDisplay.textContent = 'Rp ' + ongkir.toLocaleString('id-ID');

                        const finalTotal = baseTotal + ongkir;
                        totalFinalText.textContent = 'Rp ' + finalTotal.toLocaleString('id-ID');
                    });
                });
            </script>
        @endpush


    @endsection
