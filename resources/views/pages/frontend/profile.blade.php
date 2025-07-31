@extends('pages.frontend.parent')

@section('title', 'Profil Saya')

@section('content')
    <section class="section content-offset">
        <div class="container mt-5 pt-5">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0 fw-bold">Profil Saya</h3>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            </div>


            {{-- Info Pengguna --}}
            <div class="mb-4">
                <p class="mb-1">Nama: <strong>{{ auth()->user()->name }}</strong></p>
                <p>Email: <strong>{{ auth()->user()->email }}</strong></p>
            </div>

            {{-- Daftar Alamat --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-semibold mb-0">Alamat Pengiriman</h5>
                <button class="btn btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalAlamatBaru"
                    style="background-color: black">
                    + Tambah Alamat
                </button>
            </div>

            @forelse ($alamatList as $alamat)
                <div class="border rounded p-3 mb-3 shadow-sm bg-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $alamat->nama_penerima }}</h6>
                            <p class="mb-1 text-muted">{{ $alamat->alamat_lengkap }}</p>
                            <ul class="list-unstyled mb-2 small text-secondary">
                                <li><strong>Provinsi:</strong> {{ $alamat->provinsi }}</li>
                                <li><strong>Kota/Kabupaten:</strong> {{ $alamat->kota }}</li>
                                <li><strong>No. Telepon:</strong> {{ $alamat->no_telp }}</li>
                            </ul>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editAlamatModal{{ $alamat->id }}">
                                Edit
                            </button>
                            <form action="{{ route('alamat.destroy', $alamat->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>


                {{-- Modal Edit Alamat --}}
                <div class="modal fade" id="editAlamatModal{{ $alamat->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form method="POST" action="{{ route('alamat.update', $alamat->id) }}">
                            @csrf @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title">Edit Alamat</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Penerima</label>
                                        <input type="text" name="nama_penerima" class="form-control"
                                            value="{{ $alamat->nama_penerima }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nomor Telepon</label>
                                        <input type="number" name="no_telp" class="form-control"
                                            value="{{ $alamat->no_telp }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Provinsi</label>
                                        <input type="text" name="provinsi" class="form-control"
                                            value="{{ $alamat->provinsi }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kota/Kabupaten</label>
                                        <input type="text" name="kota" class="form-control"
                                            value="{{ $alamat->kota }}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <textarea name="alamat_lengkap" class="form-control" rows="3" required>{{ $alamat->alamat_lengkap }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-dark w-100">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>



            @empty
                <p class="text-muted">Belum ada alamat disimpan.</p>
            @endforelse
        </div>
    </section>

    {{-- Modal Tambah Alamat --}}
    <div class="modal fade" id="modalAlamatBaru" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('alamat.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Tambah Alamat Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Penerima</label>
                            <input type="text" name="nama_penerima"
                                class="form-control @error('nama_penerima') is-invalid @enderror" required>
                            @error('nama_penerima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="number" name="no_telp" min="0"
                                class="form-control @error('no_telp') is-invalid @enderror" required>
                            @error('no_telp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi"
                                class="form-control @error('provinsi') is-invalid @enderror"
                                placeholder="Contoh: Jawa Barat" required>
                            @error('provinsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kota/Kabupaten</label>
                            <input type="text" name="kota" class="form-control @error('kota') is-invalid @enderror"
                                placeholder="Contoh: Bandung" required>
                            @error('kota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" class="form-control @error('alamat_lengkap') is-invalid @enderror" rows="3"
                                placeholder="Jalan, RT/RW, patokan rumah..." required></textarea>
                            @error('alamat_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-cart w-100">Simpan Alamat</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('modalAlamatBaru'));
                modal.show();
            });
        </script>
    @endif

@endsection
