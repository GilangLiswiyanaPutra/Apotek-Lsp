@extends('layouts.app')
@section('title', 'Edit Apoteker')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Apoteker</h4>
                <form action="{{ route('apotekers.update', $apoteker->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $apoteker->nama) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $apoteker->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $apoteker->telepon) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Password Baru (opsional)</label>
                        <input type="password" name="password" class="form-control">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('apotekers.index') }}" class="btn btn-dark">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection