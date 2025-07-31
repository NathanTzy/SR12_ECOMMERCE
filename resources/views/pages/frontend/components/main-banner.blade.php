<!-- ***** Main Banner Area Start ***** -->
<div class="main-banner mb-5" id="top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="left-content">
                    <div class="thumb">
                        <div class="inner-content">
                            <h4>Jelajahi Kategori</h4>
                            <span>Temukan berbagai pilihan kategori produk menarik</span>
                            <div class="main-border-button">
                                <a href="{{ route('frontend.all-categories') }}">Lihat Semua Kategori</a>
                            </div>
                        </div>
                        <img src="{{ asset('img/sr12.png') }}" alt="Kategori Produk">
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="right-content">
                    <div class="row">
                        @foreach ($latestCategories as $kategori)
                            <div class="col-lg-6 mb-4">
                                <div class="right-first-image">
                                    <div class="thumb">
                                        <div class="inner-content">
                                            <h4>{{ $kategori->nama }}</h4>
                                            <span>Kategori terbaru kami</span>
                                        </div>
                                        <div class="hover-content">
                                            <div class="inner">
                                                <h4>{{ $kategori->nama }}</h4>
                                                <p>{{ Str::limit($kategori->deskripsi ?? 'Kategori pilihan terbaik untuk Anda.', 80) }}
                                                </p>
                                                <div class="main-border-button">
                                                    <a href="{{ route('kategori.show', $kategori->slug) }}">Discover
                                                        More</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="img-container">
                                            <img src="{{ $kategori->img ? asset('storage/' . $kategori->img) : asset('frontend/images/placeholder.jpg') }}"
                                                alt="{{ $kategori->nama }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ***** Main Banner Area End ***** -->
