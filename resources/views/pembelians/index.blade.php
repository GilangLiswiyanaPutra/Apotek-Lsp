@extends('layouts.app')

@section('title', 'Riwayat Pembelian')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Riwayat Pembelian</h4>
                <p class="card-description">Daftar transaksi pembelian dari supplier.</p>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    {{-- Form Pencarian --}}
                    <div>
                        <form method="GET" action="{{ route('pembelians.index') }}" class="d-flex">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari No. Nota / Supplier..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- Riwayat Pembelian --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nomor Nota</th>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pembelians as $pembelian)
                                <tr>
                                    <td><span class="badge badge-dark">{{ $pembelian->nomor_nota }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($pembelian->tanggal_nota)->format('d F Y') }}</td>
                                    <td>{{ $pembelian->supplier->nama }}</td>
                                    <td>
                                        <a href="{{ route('pembelians.show', $pembelian->id) }}" class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i> Detail</a>
                                        <form action="{{ route('pembelians.destroy', $pembelian->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                    data-nama="{{ $pembelian->nomor_nota }}"
                                                    data-message="Yakin hapus pembelian '{{ $pembelian->nomor_nota }}'? Stok obat akan dikembalikan dan data dihapus permanen.">
                                                <i class="mdi mdi-delete"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada riwayat pembelian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $pembelians->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
<script>
    $(document).ready(function() {
        let cartItems = [];

        // Menambahkan item ke keranjang
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

        // Mengubah jumlah item di keranjang
        $(document).on('change', '.item-jumlah', function() {
            const itemId = $(this).data('id');
            const newJumlah = parseInt($(this).val());
            const item = cartItems.find(item => item.id === itemId);

            if (item && newJumlah > 0 && newJumlah <= item.stok) {
                item.jumlah = newJumlah;
            } else if (item) {
                $(this).val(item.jumlah); // Kembalikan ke nilai valid jika input salah
            }

            renderCart();
        });

        // Menghapus item dari keranjang
        $(document).on('click', '.hapus-item', function() {
            const itemId = $(this).data('id');
            cartItems = cartItems.filter(item => item.id !== itemId);
            renderCart();
        });

        // Render keranjang pembelian
        function renderCart() {
            const keranjangBody = $('#keranjang');
            const form = $('#form-pembelian');
            keranjangBody.empty();

            let grandTotal = 0;

            form.find('input[name^="items"]').remove();

            if (cartItems.length === 0) {
                keranjangBody.html('<tr><td class="text-center text-muted py-4" colspan="4">Keranjang kosong.</td></tr>');
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
                                <input type="number" class="form-control form-control-sm item-jumlah" value="${item.jumlah}" min="1" max="${item.stok}" data-id="${item.id}" style="width: 70px;">
                            </td>
                            <td class="text-right">${formatRupiah(subtotal)}</td>
                            <td><button type="button" class="btn btn-outline-danger btn-sm hapus-item" data-id="${item.id}">&times;</button></td>
                        </tr>
                    `;
                    keranjangBody.append(row);

                    form.append(`<input type="hidden" name="items[${index}][obat_id]" value="${item.id}">`);
                    form.append(`<input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}">`);
                });
            }

            $('#total-harga').text(formatRupiah(grandTotal));
        }

        // Fungsi format rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        }
        
        // Handle delete buttons with modal
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const message = this.getAttribute('data-message');
                showDeleteModal(form, message);
            });
        });
    });
</script>
@endpush