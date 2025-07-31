@extends('layouts.app')

@section('title', 'Daftar User')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Manajemen User</h1>
                <div class="section-header-button">
                    <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah User</a>
                </div>
            </div>

            <div class="section-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">

                    <div class="card-header">
                        <h4>Data User</h4>
                    </div>

                    {{-- Filter Role --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-center flex-wrap">
                            @foreach ($roles as $r)
                                <form action="{{ route('users.index') }}" method="GET" class="mx-1">
                                    <input type="hidden" name="role" value="{{ $r }}">
                                    <button type="submit"
                                        class="btn btn-sm px-4 rounded-pill {{ request('role') == $r ? 'btn-primary' : 'btn-outline-primary' }}">
                                        {{ ucfirst($r) }}
                                    </button>
                                </form>
                            @endforeach

                            @if (request('role'))
                                <a href="{{ route('users.index') }}"
                                    class="btn btn-sm btn-outline-secondary rounded-pill px-4">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $u)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>
                                            @foreach ($u->getRoleNames() as $role)
                                                @php
                                                    $badgeColor = match ($role) {
                                                        'distributor' => 'badge-warning',
                                                        'agen' => 'badge-primary',
                                                        'subAgen' => 'badge-success',
                                                        'marketer' => 'badge-info',
                                                        'reseller' => 'badge-dark',
                                                        default => 'badge-secondary',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge {{ $badgeColor }} rounded-pill">{{ ucfirst($role) }}</span>
                                            @endforeach

                                        </td>
                                        <td>
                                            <a href="{{ route('users.show', $u->id) }}"
                                                class="btn btn-sm btn-info">Detail</a>
                                            <a href="{{ route('users.edit', $u->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('users.destroy', $u->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>

                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada user yang ditambahkan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $users->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
