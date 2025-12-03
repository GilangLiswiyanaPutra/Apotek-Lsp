@extends('layouts.app')
@section('title', 'Dashboard - Etalase Obat')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title">Etalase Obat</h4>
                        <p class="card-description">Obat yang tersedia untuk Anda.</p>
                    </div>
                    <form method="GET" action="{{ route('dashboard') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari obat..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i></button>
                        </div>
                    </form>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                     <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <div class="row">
                    @forelse ($obats as $obat)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 grid-margin stretch-card">
                            <div class="card">
                                <img class="card-img-top" src="{{ $obat->gambar ? asset('storage/' . $obat->gambar) : 'https://via.placeholder.com/300x200.png?text=Obat' }}" alt="{{ $obat->nama }}" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $obat->nama }}</h5>
                                    <p class="card-text text-muted">{{ $obat->jenis }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="font-weight-bold mb-0">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                                        <label class="badge badge-success">Stok: {{ $obat->stok }}</label>
                                    </div>
                                    <div class="d-flex mt-3">
                                        {{-- Tombol Detail bisa diaktifkan kembali jika diperlukan --}}
                                        {{-- <a href="{{ route('obats.show', $obat->id) }}" class="btn btn-outline-secondary btn-sm flex-grow-1 mr-2">Detail</a> --}}
                                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#buyModal"
                                            data-id="{{ $obat->id }}"
                                            data-nama="{{ $obat->nama }}"
                                            data-stok="{{ $obat->stok }}"
                                            data-harga="{{ $obat->harga_jual }}">
                                            <i class="mdi mdi-cart"></i> Beli
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><p class="text-center">Tidak ada obat yang tersedia saat ini.</p></div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">{{ $obats->links() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembelian -->
<div class="modal fade" id="buyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyModalLabel">Konfirmasi Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('obat.purchase') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="obat_id" id="modal-obat-id">
                    <p>Anda akan membeli: <strong id="modal-obat-nama"></strong></p>
                    <p>Harga Satuan: <span id="modal-obat-harga"></span></p>
                    <div class="form-group">
                        <label for="jumlah">Jumlah Beli</label>
                        <input type="number" name="jumlah" id="modal-jumlah" class="form-control" value="1" min="1" required>
                    </div>
                    <hr>
                    <h4>Total: <span id="modal-total-harga"></span></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi Pembelian</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- =================================================================== --}}
{{-- Kode JavaScript untuk Transaksi Pembelian --}}
{{-- =================================================================== --}}
<script>
// Pastikan semua elemen halaman sudah dimuat sebelum menjalankan skrip
$(document).ready(function() {
    
    // Event listener ini akan berjalan SETIAP KALI modal akan ditampilkan
    $('#buyModal').on('show.bs.modal', function (event) {
        
        // 1. Ambil data dari tombol yang diklik
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var modal = $(this); // Modal itu sendiri
        
        var obatId = button.data('id');
        var obatNama = button.data('nama');
        var obatStok = button.data('stok');
        var obatHarga = button.data('harga');

        // 2. Buat fungsi helper untuk memformat angka menjadi Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR', 
                minimumFractionDigits: 0 
            }).format(angka);
        }

        // 3. Isi elemen-elemen di dalam modal dengan data yang sudah diambil
        modal.find('#modal-obat-id').val(obatId);
        modal.find('#modal-obat-nama').text(obatNama);
        modal.find('#modal-obat-harga').text(formatRupiah(obatHarga));
        modal.find('#modal-jumlah').attr('max', obatStok); // Batasi jumlah beli sesuai stok
        modal.find('#modal-total-harga').text(formatRupiah(obatHarga)); // Tampilkan total harga awal

        // 4. Buat event listener untuk input jumlah
        // `.off('input')` penting untuk mencegah event listener menumpuk setiap kali modal dibuka
        $('#modal-jumlah').off('input').on('input', function() {
            var jumlah = $(this).val();
            // Pastikan jumlah tidak melebihi stok
            if (parseInt(jumlah) > parseInt(obatStok)) {
                $(this).val(obatStok);
                jumlah = obatStok;
            }
            // Hitung total harga
            var total = jumlah * obatHarga;
            // Tampilkan total harga yang sudah diformat
            modal.find('#modal-total-harga').text(formatRupiah(total));
        });
    });
});
</script>
@endpush