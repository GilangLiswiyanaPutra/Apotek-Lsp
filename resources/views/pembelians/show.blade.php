@extends('layouts.app')

@section('title', 'Detail Nota Pembelian')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                {{-- [UBAH] Kop Nota disederhanakan untuk menampilkan data yang diminta --}}
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="card-title">Detail Nota Pembelian</h4>
                        <p class="card-description mb-1"><strong>Nomor Nota:</strong> {{ $pembelian->nomor_nota }}</p>
                        <p class="card-description mb-1"><strong>Kode Obat:</strong> {{ $pembelian->kode_obat ?? 'N/A' }}</p>
                        <p class="card-description mb-1"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pembelian->tanggal_nota)->format('d F Y') }}</p>
                        <p class="card-description mb-1"><strong>Supplier:</strong> {{ $pembelian->supplier->nama }}</p>
                        <p class="card-description mb-1"><strong>Jumlah:</strong> {{ $pembelian->details->first()->jumlah ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <a href="{{ route('pembelians.index') }}" class="btn btn-dark">Kembali</a>
                        <button onclick="window.print()" class="btn btn-light"><i class="mdi mdi-printer"></i> Cetak</button>
                    </div>
                </div>
                
                {{-- Bagian rincian item dan total dihapus --}}

            </div>
        </div>
    </div>
</div>
@endsection
