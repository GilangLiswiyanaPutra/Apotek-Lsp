<!-- @extends('layouts.app')
@section('title', 'Transaksi Penjualan')

@push('styles')
<style>
    /* Styling untuk daftar produk dan keranjang */
    .product-grid { max-height: 65vh; overflow-y: auto; padding-right: 15px; }
    .product-card { 
        cursor: pointer; 
        border: 1px solid #444; 
        transition: all 0.2s ease-in-out;
    }
    .product-card:hover { 
        border-color: #007bff; 
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.1);
    }
    .cart-items { max-height: 40vh; overflow-y: auto; }
</style>
@endpush

@section('content')
<div class="row">
    {{-- Kolom Kiri: Daftar Produk / Etalase Obat --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title">Etalase Obat</h4>
                        <p class="card-description">Klik pada obat untuk menambahkannya ke keranjang.</p>
                    </div>
                    <form method="GET" action="{{ route('dashboard') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari obat..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i></button>
                        </div>
                    </form>
                </div>

                <div class="row product-grid">
                    @forelse ($obats as $obat)
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 grid-margin">
                            {{-- Kartu produk yang bisa diklik --}}
                            <div class="card product-card bg-dark" 
                                 data-id="{{ $obat->id }}" 
                                 data-nama="{{ $obat->nama }}" 
                                 data-stok="{{ $obat->stok }}"
                                 data-harga="{{ $obat->harga_jual }}">
                                <img class="card-img-top" src="{{ $obat->gambar ? asset('storage/' . $obat->gambar) : 'https://via.placeholder.com/300x200.png?text=Obat' }}" alt="{{ $obat->nama }}" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-1">{{ $obat->nama }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="font-weight-bold text-success mb-0">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</h5>
                                        <label class="badge badge-info">Stok: {{ $obat->stok }}</label>
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

    {{-- Kolom Kanan: Keranjang Belanja --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Keranjang Belanja</h4>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                     <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                
                <form action="{{ route('obat.purchase') }}" method="POST" id="form-penjualan">
                    @csrf
                    <div class="table-responsive cart-items">
                        <table class="table">
                            <tbody id="keranjang">
                                <!-- Item keranjang akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between font-weight-bold">
                        <h5>TOTAL</h5>
                        <h5 id="total-harga">Rp 0</h5>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mt-3">PROSES PESANAN</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Kode JavaScript untuk Transaksi Pembelian --}}
<script>
$(document).ready(function() {
    let cartItems = [];

    // Event saat kartu produk diklik
    $(document).on('click', '.product-card', function() {
        const itemData = {
            id: $(this).data('id'),
            nama: $(this).data('nama'),
            harga: parseFloat($(this).data('harga')),
            stok: parseInt($(this).data('stok')),
        };

        const existingItem = cartItems.find(item => item.id === itemData.id);
        if (existingItem) {
            if (existingItem.jumlah < itemData.stok) existingItem.jumlah++;
        } else {
            itemData.jumlah = 1;
            cartItems.push(itemData);
        }
        renderCart();
    });

    // Event saat jumlah di keranjang diubah
    $(document).on('change', '.item-jumlah', function() {
        const itemId = $(this).data('id');
        const newJumlah = parseInt($(this).val());
        const item = cartItems.find(item => item.id === itemId);
        if (item && newJumlah > 0 && newJumlah <= item.stok) {
            item.jumlah = newJumlah;
        } else if (item) {
            $(this).val(item.jumlah);
        }
        renderCart();
    });

    // Event saat tombol hapus di keranjang diklik
    $(document).on('click', '.hapus-item', function() {
        const itemId = $(this).data('id');
        cartItems = cartItems.filter(item => item.id !== itemId);
        renderCart();
    });

    // Fungsi untuk menampilkan ulang keranjang
    function renderCart() {
        const keranjangBody = $('#keranjang');
        const form = $('#form-penjualan');
        keranjangBody.empty();
        let grandTotal = 0;

        form.find('input[name^="items"]').remove();

        if (cartItems.length === 0) {
            keranjangBody.html('<tr><td class="text-center text-muted py-4">Keranjang kosong.</td></tr>');
        } else {
            cartItems.forEach((item, index) => {
                const subtotal = item.harga * item.jumlah;
                grandTotal += subtotal;
                const row = `
                    <tr>
                        <td>
                            ${item.nama} <br>
                            <small class="text-muted">${formatRupiah(item.harga)}</small>
                        </td>
                        <td class="text-right">
                            <input type="number" class="form-control form-control-sm item-jumlah bg-dark" value="${item.jumlah}" min="1" max="${item.stok}" data-id="${item.id}" style="width: 70px;">
                        </td>
                        <td class="text-right">${formatRupiah(subtotal)}</td>
                        <td><button type="button" class="btn btn-outline-danger btn-sm hapus-item" data-id="${item.id}">&times;</button></td>
                    </tr>
                `;
                keranjangBody.append(row);
                
                // Tambahkan input hidden untuk dikirim ke controller
                form.append(`<input type="hidden" name="items[${index}][obat_id]" value="${item.id}">`);
                form.append(`<input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}">`);
            });
        }
        $('#total-harga').text(formatRupiah(grandTotal));
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }
});
</script>
@endpush -->
