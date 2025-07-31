@extends('pages.frontend.parent')

@section('title', 'All Categories')

@section('content')
    <section class="section content-offset">
        <div class="container">

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
                        <h2>All Categories</h2>
                        <span>Jelajahi seluruh kategori produk yang tersedia</span>
                    </div>
                </div>
            </div>

            {{-- Kategori Cards --}}
            <div class="row">
                @foreach ($categories as $kategori)
                    <div class="col-12 mb-4">
                        <a href="{{ route('kategori.show', $kategori->slug) }}" class="text-decoration-none">
                            <div class="category-card d-flex align-items-center"
                                style="
                       background-image: url('{{ $kategori->img ? asset('storage/' . $kategori->img) : asset('frontend/images/placeholder.jpg') }}');
                       background-size: cover;
                       background-position: center;
                       height: 200px;
                       border-radius: 12px;
                       position: relative;
                       overflow: hidden;
                       color: white;
                   ">
                                <div
                                    style="
                      position: absolute;
                      top: 0;
                      left: 0;
                      width: 100%;
                      height: 100%;
                      background: rgba(0, 0, 0, 0.5);
                      display: flex;
                      align-items: center;
                      justify-content: space-between;
                      padding: 20px 30px;
                  ">
                                    <div>
                                        <h3 class="mb-1">{{ $kategori->nama }}</h3>
                                        <p class="mb-0">{{ Str::limit($kategori->deskripsi, 80) }}</p>
                                    </div>
                                    <div>
                                        <i class="fa fa-arrow-right fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endsection
