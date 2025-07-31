@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Pengguna</h1>
            </div>

            <div class="section-body">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Informasi Akun</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered mb-0">
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>{{ $user->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Bergabung</th>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <th>Tempat Lahir</th>
                                <td>{{ $user->tempat_lahir }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td>{{ $user->tanggal_lahir }}</td>
                            </tr>
                            <tr>
                                <th>Kecamatan</th>
                                <td>{{ $user->kecamatan }}</td>
                            </tr>
                            <tr>
                                <th>Kota</th>
                                <td>{{ $user->kota }}</td>
                            </tr>
                            <tr>
                                <th>Provinsi</th>
                                <td>{{ $user->provinsi }}</td>
                            </tr>
                            <tr>
                                <th>No WhatsApp</th>
                                <td>{{ $user->no_wa }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Username</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{ $user->getRoleNames()->first() }}</td>
                            </tr>
                        </table>
                    </div>
                    <button class="btn btn-success mb-3 mx-5" onclick="copyToClipboard()">
                        <i class="fas fa-copy"></i> Salin ke Spreadsheet
                    </button>
                </div>

                <a href="{{ route('users.index') }}" class="btn btn-secondary mt-4">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </section>
    </div>
@endsection

<script>
    function copyToClipboard() {
        let table = document.querySelector(".table");
        let rows = table.querySelectorAll("tr");
        let output = "";

        rows.forEach((row, index) => {
            let td = row.querySelectorAll("td");
            if (td.length) {
                output += td[0].innerText.trim();
                if (index < rows.length - 1) output += "\t"; // pakai tab antar kolom
            }
        });

        let textarea = document.createElement("textarea");
        textarea.value = output;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
    }
</script>
