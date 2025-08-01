@extends('layouts.app')

@section('title', 'Edit Item')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Item</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('item.index') }}">Item</a></div>
                    <div class="breadcrumb-item active">Edit</div>
                </div>
            </div>

            <div class="section-body">
                {{-- Notifikasi Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible show fade">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('item.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-header">
                            <h4>Form Edit Item</h4>
                        </div>
                        <div class="card-body">
                            {{-- Nama --}}
                            <div class="form-group">
                                <label>Nama Item</label>
                                <input type="text" name="nama"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $item->nama) }}">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ (old('category_id') ?? $item->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Harga --}}
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="number" name="harga"
                                    class="form-control @error('harga') is-invalid @enderror"
                                    value="{{ old('harga', $item->harga) }}">
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kuantitas --}}
                            <div class="form-group">
                                <label>Kuantitas</label>
                                <input type="number" name="kuantitas"
                                    class="form-control @error('kuantitas') is-invalid @enderror"
                                    value="{{ old('kuantitas', $item->kuantitas) }}">
                                @error('kuantitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- Deskripsi --}}
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Gambar Lama --}}
                            <div class="form-group">
                                <label>Gambar Saat Ini</label><br>
                                @if ($item->img && file_exists(public_path('storage/' . $item->img)))
                                    <img src="{{ asset('storage/' . $item->img) }}" alt="Item" width="120"
                                        class="rounded">
                                @else
                                    <p class="text-muted">Tidak ada gambar tersedia.</p>
                                @endif
                            </div>

                            {{-- Ganti Gambar --}}
                            <div class="form-group">
                                <label>Ganti Gambar (Opsional)</label>
                                <input type="file" name="img" class="form-control @error('img') is-invalid @enderror"
                                    accept="image/*">
                                @error('img')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('item.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
