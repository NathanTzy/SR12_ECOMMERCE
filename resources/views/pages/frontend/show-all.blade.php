@extends('pages.frontend.parent')

@section('title', $kategori->nama)

@section('content')
    <section class="section content-offset" id="products">
        <div class="container">

            {{-- Tombol Back di kiri atas --}}
            <div class="mt-5 pt-5">
                <a href="{{ route('frontend.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Home
                </a>
            </div>

            {{-- Judul --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>{{ $kategori->nama }}</h2>
                        <span>Produk-produk dalam kategori ini.</span>
                    </div>
                </div>
            </div>

            {{-- Daftar Produk --}}
            <div class="row">
                @forelse ($items as $item)
                    <div class="col-lg-4 mb-4">
                        <a href="{{ route('produk.detail', $item->id) }}" class="text-decoration-none">
                            <div class="item">
                                <div class="thumb">
                                    <div class="img-thumb-box">
                                        <img src="{{ asset('storage/' . $item->img) }}" alt="{{ $item->nama }}">
                                    </div>
                                </div>
                                <div class="down-content">
                                    <h4>{{ $item->nama }}</h4>
                                    @php
                                        $hargaDiskon = $diskonPersen
                                            ? $item->harga - ($item->harga * $diskonPersen) / 100
                                            : null;
                                    @endphp

                                    @if ($hargaDiskon)
                                        <span class="text-muted text-decoration-line-through">Rp
                                            {{ number_format($item->harga, 0, ',', '.') }}</span><br>
                                        <span class="text-danger">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                                    @else
                                        <span>Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>Belum ada produk di kategori ini.</p>
                    </div>
                @endforelse
            </div>



            {{-- Pagination --}}
            @if ($items->hasPages())
                <div class="row">
                    <div class="col-lg-12 d-flex justify-content-center">
                        {{ $items->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            @endif

        </div>
    </section>
@endsection
