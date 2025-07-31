@extends('layouts.app')

@section('title', 'Tambah Pembayaran')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Tambah Metode Pembayaran</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('payment.index') }}">Pembayaran</a></div>
                <div class="breadcrumb-item active">Tambah</div>
            </div>
        </div>

        <div class="section-body">

            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible show fade">
                    {{ session('success') }}
                    <button class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

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

            <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>Form Metode Pembayaran</h4>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label>Nama Bank</label>
                            <input type="text" name="nama_bank" class="form-control @error('nama_bank') is-invalid @enderror"
                                value="{{ old('nama_bank') }}" required>
                            @error('nama_bank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Atas Nama</label>
                            <input type="text" name="atas_nama" class="form-control @error('atas_nama') is-invalid @enderror"
                                value="{{ old('atas_nama') }}" required>
                            @error('atas_nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>No Rekening</label>
                            <input type="number" name="no_rekening" class="form-control @error('no_rekening') is-invalid @enderror"
                                value="{{ old('no_rekening') }}" required>
                            @error('no_rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('payment.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>

        </div>
    </section>
</div>
@endsection
