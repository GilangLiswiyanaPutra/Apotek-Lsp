@extends('layouts.app')
@section('title', 'Edit Supplier')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Supplier</h4>
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Kode</label>
                        <input type="text" class="form-control" value="{{ $supplier->kode }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Nama Supplier</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $supplier->nama) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $supplier->alamat) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Kota</label>
                        <input type="text" name="kota" class="form-control" value="{{ old('kota', $supplier->kota) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $supplier->telepon) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-dark">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection