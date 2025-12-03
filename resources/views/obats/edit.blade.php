@extends('layouts.app')

@section('title', 'Edit Data Obat')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Data Obat</h4>
                <p class="card-description">Ubah field yang diperlukan.</p>
                
                {{-- [TAMBAH] Blok untuk menampilkan error validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="forms-sample" action="{{ route('obats.update', $obat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="kode_obat">Kode Obat</label>
                        <input type="text" class="form-control" id="kode_obat" value="{{ $obat->kode_obat }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Obat</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $obat->nama) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="jenis">Jenis Obat</label>
                        <input type="text" class="form-control" id="jenis" name="jenis" value="{{ old('jenis', $obat->jenis) }}" required>
                    </div>
                    
                    {{-- [TAMBAH] Input field yang hilang --}}
                    <div class="form-group">
                        <label for="satuan">Satuan</label>
                        <input type="text" class="form-control" id="satuan" name="satuan" value="{{ old('satuan', $obat->satuan) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="harga_jual">Harga Jual (Rp)</label>
                        <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $obat->harga_jual) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="harga_beli">Harga Beli (Rp)</label>
                        <input type="number" class="form-control" id="harga_beli" name="harga_beli" value="{{ old('harga_beli', $obat->harga_beli) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" value="{{ old('stok', $obat->stok) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="supplier">Supplier</label>
                        <input type="text" class="form-control" id="supplier" name="supplier" value="{{ old('supplier', $obat->supplier) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="tgl_kadaluarsa">Tanggal Kadaluarsa <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tgl_kadaluarsa" name="tgl_kadaluarsa" value="{{ old('tgl_kadaluarsa', $obat->tgl_kadaluarsa ? $obat->tgl_kadaluarsa->format('Y-m-d') : '') }}" required>
                        <small class="form-text text-muted">Tanggal kadaluarsa obat</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Gambar Saat Ini</label><br>
                        @if($obat->gambar)
                            <img src="{{ asset('storage/' . $obat->gambar) }}" alt="{{ $obat->nama }}" width="150" class="rounded">
                        @else
                            <p>Tidak ada gambar.</p>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="gambar">Upload Gambar Baru (Opsional)</label>
                        <input type="file" name="gambar" class="form-control">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mr-2">Update</button>
                    <a href="{{ route('obats.index') }}" class="btn btn-dark">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection