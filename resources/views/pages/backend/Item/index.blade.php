@extends('layouts.app')

@section('title', 'Daftar Item')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Manajemen Item</h1>
                <div class="section-header-button">
                    <a href="{{ route('item.create') }}" class="btn btn-primary">Tambah Item</a>
                </div>
            </div>

            <div class="section-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible show fade">
                        {{ session('success') }}
                        <button class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                {{-- Filter Kategori --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-center flex-wrap">
                        @foreach ($kategoriList as $kategori)
                            <form action="{{ route('item.index') }}" method="GET" class="mr-2 mb-2">
                                <input type="hidden" name="kategori" value="{{ $kategori->id }}">
                                <button type="submit"
                                    class="btn btn-sm px-4 rounded-pill {{ request('kategori') == $kategori->id ? 'btn-primary' : 'btn-outline-primary' }}">
                                    {{ $kategori->nama }}
                                </button>
                            </form>
                        @endforeach

                        @if (request('kategori'))
                            <a href="{{ route('item.index') }}"
                                class="btn btn-sm btn-outline-secondary rounded-pill px-4 mb-2 ml-2">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Tabel Item --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Data Item</h4>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle" style="min-width: 1000px">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gambar</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    @foreach ($discounts as $role => $percent)
                                        <th class="text-danger">Harga {{ ucfirst($role) }}</th>
                                    @endforeach
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    @php
                                        $harga = $item->harga;
                                        $hargaAgen = $harga - ($harga * ($discounts['agen'] ?? 0)) / 100;
                                        $hargaSubAgen = $harga - ($harga * ($discounts['subAgen'] ?? 0)) / 100;
                                    @endphp

                                    <tr @if ($item->kuantitas == 0) class="table-danger" @endif>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($item->img && file_exists(public_path('storage/' . $item->img)))
                                                <img src="{{ asset('storage/' . $item->img) }}" alt="Item"
                                                    width="70" class="img-thumbnail rounded">
                                            @else
                                                <span class="text-muted">Tidak ada gambar</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->category->nama ?? '-' }}</td>
                                        <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                                        @foreach ($discounts as $role => $percent)
                                            @php
                                                $hargaDiskon = $item->harga - ($item->harga * $percent) / 100;
                                            @endphp
                                            <td class="text-danger">
                                                Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                                <br>
                                                <small class="text-muted">-{{ $percent }}%</small>
                                            </td>
                                        @endforeach

                                        <td>{{ $item->kuantitas }}</td>
                                        <td>
                                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                                <a href="{{ route('item.edit', $item->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('item.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Yakin hapus item ini?')">Hapus</button>
                                                </form>
                                                <a href="{{ route('item.riwayat', $item->id) }}"
                                                    class="btn btn-sm btn-info">Riwayat</a>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada item ditambahkan.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
