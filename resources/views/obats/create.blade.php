@extends('layouts.app')

@section('title', 'Tambah Data Obat & Nota Pembelian')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Data Obat</h4>
                <p class="card-description">Menambahkan obat baru akan otomatis membuat nota pembelian.</p>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="forms-sample" action="{{ route('obats.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h5>Informasi Nota Pembelian</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_id">Supplier</label>
                                <select class="form-control" id="supplier_id" name="supplier_id" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_nota">Tanggal Nota</label>
                                <input type="date" class="form-control" id="tanggal_nota" name="tanggal_nota" value="{{ old('tanggal_nota', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <h5>Informasi Obat</h5>

                    {{-- [TAMBAH] Field untuk Kode Obat --}}
                    <div class="form-group">
                        <label for="kode_obat">Kode Obat</label>
                        <input type="text" class="form-control" id="kode_obat" placeholder="Akan dibuat secara otomatis" disabled>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Obat</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Paracetamol" value="{{ old('nama') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="gambar">Gambar Obat (Opsional)</label>
                        <input type="file" name="gambar" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis Obat</label>
                        <input type="text" class="form-control" id="jenis" name="jenis" placeholder="Contoh: Tablet" value="{{ old('jenis') }}" required>
                    </div>
                     <div class="form-group">
                        <label for="satuan">Satuan</label>
                        <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Contoh: Strip" value="{{ old('satuan') }}" required>
                    </div>
                     <div class="form-group">
                        <label for="harga_jual">Harga Jual (Rp)</label>
                        <input type="number" class="form-control" id="harga_jual" name="harga_jual" placeholder="15000" value="{{ old('harga_jual') }}" required>
                    </div>
                     <div class="form-group">
                        <label for="harga_beli">Harga Beli (Rp)</label>
                        <input type="number" class="form-control" id="harga_beli" name="harga_beli" placeholder="12000" value="{{ old('harga_beli') }}" required>
                    </div>
                     <div class="form-group">
                        <label for="stok">Stok Awal (Jumlah Beli)</label>
                        <input type="number" class="form-control" id="stok" name="stok" placeholder="100" value="{{ old('stok') }}" required min="1">
                    </div>
                     <div class="form-group">
                        <label for="tgl_kadaluarsa">Tanggal Kadaluarsa <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tgl_kadaluarsa" name="tgl_kadaluarsa" value="{{ old('tgl_kadaluarsa') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        <small class="form-text text-muted">Tanggal kadaluarsa tidak boleh hari ini atau sudah lewat</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                    <a href="{{ route('obats.index') }}" class="btn btn-dark">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection