@foreach ($categories as $kategori)
    @if ($kategori->item->count() > 0)
        @php
            $items = $kategori->item;
            $minItemCount = 4;

            if ($items->count() < $minItemCount) {
                $loopedItems = collect();

                while ($loopedItems->count() < $minItemCount) {
                    $loopedItems = $loopedItems->merge($items);
                }

                $items = $loopedItems->take($minItemCount);
            }
        @endphp

        <section class="section">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="mb-1">{{ $kategori->nama }}'s Latest</h2>
                        <span class="text-muted">Produk pilihan dari kategori {{ $kategori->nama }}.</span>
                    </div>
                    <a href="{{ route('kategori.show', $kategori->slug ?? $kategori->id) }}" class="btn btn-outline-dark">
                        See All
                    </a>
                </div>


                <div class="custom-carousel-wrapper">
                    <div class="custom-carousel" id="carousel-{{ $kategori->id }}">
                        @foreach ($items as $item)
                            <div class="custom-carousel-item mb-5">
                                <div class="thumb">
                                    <a href="{{ route('produk.detail', $item->id) }}">
                                        <img src="{{ asset('storage/' . $item->img) }}" alt="{{ $item->nama }}">
                                    </a>
                                </div>
                                <div class="down-content">
                                    <h4>
                                        <a href="{{ route('produk.detail', $item->id) }}"
                                            class="text-dark">{{ $item->nama }}</a>
                                    </h4>
                                    <span>
                                        @if (isset($discount))
                                            @php
                                                $diskonPersen = $discount->persen;
                                                $hargaDiskon = $item->harga - ($item->harga * $diskonPersen) / 100;
                                            @endphp

                                            <small class="text-muted" style="text-decoration: line-through;">
                                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                                            </small>
                                            <br>
                                            <strong class="text-danger">
                                                Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                            </strong>
                                        @else
                                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                                        @endif
                                    </span>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </section>
    @endif
@endforeach
