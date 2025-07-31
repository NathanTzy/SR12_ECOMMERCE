@extends('layouts.app')
@section('title', 'Diskon')
@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Manajemen Diskon</h1>
            <div class="section-header-button">
                <a href="{{ route('discount.create') }}" class="btn btn-primary">Tambah Diskon</a>
            </div>
        </div>

        <div class="section-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible show fade">
                    {{ session('success') }}
                    <button class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="card">
                <div class="card-header"><h4>Daftar Diskon</h4></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Role</th>
                                <th>Persen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($discounts as $discount)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst($discount->role) }}</td>
                                <td>{{ $discount->persen }}%</td>
                                <td>
                                    <a href="{{ route('discount.edit', $discount->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('discount.destroy', $discount->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($discounts->isEmpty())
                                <tr><td colspan="4" class="text-center">Belum ada diskon.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
