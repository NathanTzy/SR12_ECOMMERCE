@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Kategori</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('category.index') }}">Kategori</a></div>
                    <div class="breadcrumb-item active">Edit</div>
                </div>
            </div>

            <div class="section-body">
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

                <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-header">
                            <h4>Form Edit Kategori</h4>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label>Nama Kategori</label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $category->nama) }}">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Gambar Lama --}}
                            <div class="form-group">
                                <label>Gambar Saat Ini</label><br>
                                @if ($category->img && file_exists(public_path('storage/' . $category->img)))
                                    <img src="{{ asset('storage/' . $category->img) }}" alt="Gambar" width="120" class="rounded">
                                @else
                                    <p class="text-muted">Tidak ada gambar tersedia.</p>
                                @endif
                            </div>

                            {{-- Ganti Gambar --}}
                            <div class="form-group">
                                <label>Ganti Gambar (Opsional)</label>
                                <input type="file" name="img" class="form-control @error('img') is-invalid @enderror" accept="image/*">
                                @error('img')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('category.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                        </div>
                    </div>
                </form>

            </div>
        </section>
    </div>
@endsection
