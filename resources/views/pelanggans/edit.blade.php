@extends('layouts.app')
@section('title', 'Edit Pelanggan')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Pelanggan</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <form action="{{ route('pelanggans.update', $pelanggan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <h5>Data Login</h5>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $pelanggan->user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $pelanggan->user->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Password Baru (opsional)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                     <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <hr>
                    <h5>Data Profil Pelanggan</h5>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Kota</label>
                        <input type="text" name="kota" class="form-control" value="{{ old('kota', $pelanggan->kota) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $pelanggan->telepon) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('pelanggans.index') }}" class="btn btn-dark">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection