@extends('pages.frontend.parent')

@section('title', $item->nama)

@section('content')
    <section class="section content-offset" id="product">
        <div class="container mt-5 pt-5">

            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mb-4">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>

            <div class="row">
                {{-- Foto Produk --}}
                <div class="col-lg-6 mb-4">
                    <div class="img-wrapper overflow-hidden" style="aspect-ratio: 4/3; background: #f8f9fa;">
                        <img src="{{ asset('storage/' . $item->img) }}" alt="{{ $item->nama }}" class="w-100 h-100"
                            style="object-fit: cover;">
                    </div>
                </div>

                {{-- Detail Produk --}}
                <div class="col-lg-6">
                    <div class="right-content">
                        <h3 class="mb-3">{{ $item->nama }}</h3>

                        {{-- Harga Satuan --}}
                        <div class="mb-3">
                            <p class="mb-1 text-muted">Harga Satuan</p>
                            <div class="bg-light border p-3">
                                <h4 class="text-success fw-semibold mb-0">Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>

                        {{-- Stok --}}
                        <p class="text-muted mb-2">Stok tersedia: <strong>{{ $item->kuantitas }}</strong></p>

                        {{-- Deskripsi --}}
                        <p class="mb-4">{{ $item->deskripsi ?? 'Tidak ada deskripsi untuk produk ini.' }}</p>

                        {{-- Jumlah --}}
                        <div class="mb-3">
                            <label for="quantity" class="form-label fw-bold">Jumlah</label>
                            <div class="d-flex align-items-center" style="max-width: 180px;">
                                <button class="btn btn-outline-secondary" id="minus">-</button>
                                <input type="number" id="qty" class="form-control text-center mx-2" value="1"
                                    min="1" max="{{ $item->kuantitas }}">
                                <button class="btn btn-outline-secondary" id="plus">+</button>
                            </div>
                        </div>

                        {{-- Total Harga (satu baris) --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Total Harga:</span>
                            @if ($hargaSetelahDiskon)
                                <div class="d-flex flex-column align-items-end">
                                    <p style="margin: 0; text-decoration: line-through; color: #999;">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </p>
                                    <p id="total-harga" style="font-weight: 600; font-size: 1.1rem; color: #000;">
                                        Rp {{ number_format($hargaSetelahDiskon, 0, ',', '.') }}
                                        <small class="text-success">(-{{ $diskonPersen }}%)</small>
                                    </p>
                                </div>
                            @else
                                <p id="total-harga" style="font-weight: 600; font-size: 1rem; color: #000;">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </p>
                            @endif

                        </div>


                        {{-- Tombol Tambah ke Keranjang --}}
                        @if ($item->kuantitas > 0)
                            <form method="POST" action="{{ route('cart.store') }}">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <input type="hidden" name="qty" id="formQty" value="1">
                                <button type="submit" class="btn-cart text-white w-100"
                                    style="border-radius: 0; box-shadow: none;">
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary w-100" disabled style="border-radius: 0;">
                                Stok Habis
                            </button>
                        @endif


                    </div>
                </div>
            </div>

        </div>
    </section>


    {{-- Script Interaktif --}}
    @php
        $hargaFinal = $hargaSetelahDiskon ?? $item->harga;
    @endphp

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const hargaSatuan = {{ $hargaFinal }};
                const stok = {{ $item->kuantitas }};
                const qtyInput = document.getElementById('qty');
                const totalEl = document.getElementById('total-harga');
                const plusBtn = document.getElementById('plus');
                const minusBtn = document.getElementById('minus');

                function formatRupiah(angka) {
                    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }

                function updateTotal() {
                    let qty = parseInt(qtyInput.value);
                    if (qty > stok) {
                        qty = stok;
                        qtyInput.value = stok;
                    }
                    if (qty < 1 || isNaN(qty)) {
                        qty = 1;
                        qtyInput.value = 1;
                    }
                    const total = qty * hargaSatuan;

                    @if ($hargaSetelahDiskon)
                        totalEl.innerHTML = 'Rp ' + formatRupiah(total) +
                            ' <small class="text-success">(-{{ $diskonPersen }}%)</small>';
                    @else
                        totalEl.innerText = 'Rp ' + formatRupiah(total);
                    @endif

                    const formQty = document.getElementById('formQty');
                    if (formQty) {
                        formQty.value = qty;
                    }
                }


                plusBtn.addEventListener('click', function() {
                    let qty = parseInt(qtyInput.value);
                    if (qty < stok) {
                        qtyInput.value = qty + 1;
                        updateTotal();
                    }
                });

                minusBtn.addEventListener('click', function() {
                    let qty = parseInt(qtyInput.value);
                    if (qty > 1) {
                        qtyInput.value = qty - 1;
                        updateTotal();
                    }
                });

                qtyInput.addEventListener('input', updateTotal);
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const img = document.querySelector('.img-wrapper img');
                if (img) {
                    img.style.cursor = 'zoom-in';
                    img.addEventListener('click', function() {
                        const zoomModal = new bootstrap.Modal(document.getElementById('zoomModal'));
                        zoomModal.show();
                    });
                }
            });
        </script>
    @endpush

    <!-- Modal Zoom -->
    <div class="modal fade" id="zoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <img src="{{ asset('storage/' . $item->img) }}" alt="Zoom" class="w-100 rounded">
        </div>
    </div>

@endsection
