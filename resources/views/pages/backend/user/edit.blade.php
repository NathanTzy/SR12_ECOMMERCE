@extends('layouts.app')

@section('title', 'Edit User')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit User</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('users.index') }}">Manajemen User</a></div>
                    <div class="breadcrumb-item active">Edit</div>
                </div>
            </div>

            <div class="section-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Edit User</h4>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control"
                                    value="{{ old('nama_lengkap', $user->nama_lengkap) }}">
                            </div>

                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <option value="L"
                                        {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="P"
                                        {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                            </div>
                        
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control"
                                    value="{{ old('tempat_lahir', $user->tempat_lahir) }}">
                            </div>

                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control"
                                    value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                            </div>

                            <div class="form-group">
                                <label>Kecamatan</label>
                                <input type="text" name="kecamatan" class="form-control"
                                    value="{{ old('kecamatan', $user->kecamatan) }}">
                            </div>

                            <div class="form-group">
                                <label>Kota/Kab</label>
                                <input type="text" name="kota" class="form-control"
                                    value="{{ old('kota', $user->kota) }}">
                            </div>

                            <div class="form-group">
                                <label>Provinsi</label>
                                <input type="text" name="provinsi" class="form-control"
                                    value="{{ old('provinsi', $user->provinsi) }}">
                            </div>

                            <div class="form-group">
                                <label>No WhatsApp</label>
                                <input type="text" name="no_wa" class="form-control"
                                    value="{{ old('no_wa', $user->no_wa) }}">
                            </div>

                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" class="form-control @error('role') is-invalid @enderror">
                                    <option value="">-- Pilih Role --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ (old('role') ?? $user->getRoleNames()->first()) === $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-3">
                                <label>Role Sekarang</label><br>
                                @foreach ($user->getRoleNames() as $role)
                                    <span
                                        class="badge 
                                    {{ $role == 'agen'
                                        ? 'badge-primary'
                                        : ($role == 'subAgen'
                                            ? 'badge-success'
                                            : ($role == 'distributor'
                                                ? 'badge-warning text-dark'
                                                : 'badge-secondary')) }}">
                                        {{ ucfirst($role) }}
                                    </span>
                                @endforeach
                            </div>

                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
