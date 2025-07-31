@extends('pages.frontend.parent')

@section('title', 'Tambah Alamat')

@section('content')
<section class="section content-offset">
    <div class="container mt-5 pt-5">
        <h3 class="mb-4 fw-bold">Tambah Alamat Baru</h3>

        <form method="POST" action="{{ route('alamat.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Penerima</label>
                <input type="text" name="nama_penerima" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Alamat</button>
        </form>
    </div>
</section>
@endsection
