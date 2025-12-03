@extends('layouts.app')
@section('title', 'Tambah Supplier')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Supplier</h4>
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Supplier</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Kota</label>
                        <input type="text" name="kota" class="form-control" value="{{ old('kota') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-dark">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection