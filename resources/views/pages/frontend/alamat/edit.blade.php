@extends('pages.frontend.parent')

@section('title', 'Edit Alamat')

@section('content')
<section class="section content-offset">
    <div class="container mt-5 pt-5">
        <h3 class="mb-4 fw-bold">Edit Alamat</h3>

        <form method="POST" action="{{ route('alamat.update', $alamat->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Penerima</label>
                <input type="text" name="nama_penerima" class="form-control" value="{{ $alamat->nama_penerima }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" class="form-control" rows="3" required>{{ $alamat->alamat_lengkap }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</section>
@endsection
