@extends('layouts.app')

@section('title', 'Daftar Metode Pembayaran')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Manajemen Pembayaran</h1>
            <div class="section-header-button">
                <a href="{{ route('payment.create') }}" class="btn btn-primary">Tambah Pembayaran</a>
            </div>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible show fade">
                    {{ session('success') }}
                    <button class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Data Metode Pembayaran</h4>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>              
                                <th>Bank</th>
                                <th>Atas Nama</th>
                                <th>No Rekening</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payment->nama_bank }}</td>
                                    <td>{{ $payment->atas_nama }}</td>
                                    <td>{{ $payment->no_rekening }}</td>
                                    <td>
                                        <a href="{{ route('payment.edit', $payment->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('payment.destroy', $payment->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus metode pembayaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada metode pembayaran ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
