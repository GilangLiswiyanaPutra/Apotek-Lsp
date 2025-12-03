@extends('layouts.app')

@section('title', 'Detail Obat: ' . $obat->nama)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($obat->gambar)
                            <img src="{{ asset('storage/' . $obat->gambar) }}" class="img-fluid rounded" alt="{{ $obat->nama }}">
                        @else
                            <img src="https://via.placeholder.com/400x400.png?text=Tidak+Ada+Gambar" class="img-fluid rounded" alt="Tidak ada gambar">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h3 class="card-title">{{ $obat->nama }}</h3>
                        <p class="text-muted">{{ $obat->jenis }}</p>
                        <table class="table table-striped">
                            <tr>
                                <th>Kode Obat</th> 
                                <td>{{ $obat->kode_obat }}</td>
                            </tr>
                            <tr>
                                <th>Satuan</th>
                                <td>{{ $obat->satuan }}</td>
                            </tr>
                            <tr>
                                <th>Stok</th>
                                <td>{{ $obat->stok }}</td>
                            </tr>
                            <tr>
                                <th>Harga Beli</th>
                                <td>Rp {{ number_format($obat->harga_beli, 0, ',', '.') }}</td>
                            </tr>
                             <tr>
                                <th>Harga Jual</th>
                                <td>Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td>{{ $obat->supplier }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Kadaluarsa</th>
                                <td>
                                    @if($obat->tgl_kadaluarsa)
                                        <span class="{{ $obat->isExpired() ? 'text-danger font-weight-bold' : '' }}">
                                            {{ $obat->tgl_kadaluarsa->format('d F Y') }}
                                        </span>
                                        @if($obat->getDaysUntilExpiration() !== null)
                                            <br><small class="text-muted">
                                                @if($obat->isExpired())
                                                    Telah kadaluarsa {{ abs($obat->getDaysUntilExpiration()) }} hari yang lalu
                                                @else
                                                    {{ $obat->getDaysUntilExpiration() }} hari lagi
                                                @endif
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">Tidak ada data</span>
                                    @endif
                                </td>
                            </tr>
                             <tr>
                                <th>Ditambahkan Pada</th>
                                <td>{{ $obat->created_at->format('d F Y') }}</td>
                            </tr>
                             <tr>
                                <th>Terakhir Diperbarui</th>
                                <td>{{ $obat->updated_at->format('d F Y') }}</td>
                            </tr>
                        </table>
                        <div class="mt-4">
                            <a href="{{ route('obats.edit', $obat->id) }}" class="btn btn-warning">Edit</a>
                            <a href="{{ route('obats.index') }}" class="btn btn-dark">Kembali ke Daftar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection