@extends('layouts.app')

@section('title', 'Edit Diskon')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Diskon</h1>
            </div>

            <div class="section-body">
                <form action="{{ route('discount.update', $discount->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="agen" {{ old('role', $discount->role) == 'agen' ? 'selected' : '' }}>
                                        Agen</option>
                                    <option value="subAgen"
                                        {{ old('role', $discount->role) == 'subAgen' ? 'selected' : '' }}>Sub Agen</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Persentase Diskon (%)</label>
                                <input type="number" name="persen"
                                    class="form-control @error('persen') is-invalid @enderror"
                                    value="{{ old('persen', $discount->persen) }}" required>
                                @error('persen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('discount.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
