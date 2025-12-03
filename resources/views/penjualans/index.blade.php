@extends('layouts.app')
@section('title', 'Transaksi Penjualan')

@push('styles')
<style>
    /* Enhanced styling untuk daftar produk dan keranjang */
    .product-grid { 
        max-height: 70vh; 
        overflow-y: auto; 
        padding-right: 15px; 
    }
    
    .product-card { 
        cursor: pointer; 
        border: 2px solid #404040; 
        transition: all 0.3s ease-in-out;
        border-radius: 10px;
        background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
    }
    

    
    .product-card:hover { 
        border-color: #007bff; 
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
        background: linear-gradient(145deg, #1a1a1a, #2a2a2a);
    }
    
    /* Loading state untuk card yang diklik */
    .product-card.loading {
        pointer-events: none;
        opacity: 0.7;
    }
    
    .product-card.loading::before {
        content: '⏳ Menambahkan...';
        opacity: 1;
        background: rgba(40, 167, 69, 0.9);
    }
    
    .product-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
        background: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-size: 40px;
        position: relative;
        z-index: 2; /* Ensure image is above the overlay */
        transition: all 0.3s ease;
    }
    
    .product-image:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
    }
    
    .product-image img:hover {
        filter: brightness(1.1);
    }
    
    /* Image click indicator */
    .product-image::after {
        content: '🔍 Klik untuk melihat gambar';
        position: absolute;
        bottom: 5px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 500;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 4;
        pointer-events: none;
        white-space: nowrap;
    }
    
    .product-image:hover::after {
        opacity: 1;
    }
    
    .cart-items { 
        max-height: 45vh; 
        overflow-y: auto; 
    }
    
    .cart-card {
        border-radius: 15px;
        border: 2px solid #404040;
        background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
        border-radius: 25px;
        padding: 12px 25px;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(45deg, #0056b3, #007bff);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
    }
    
    .stock-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(40, 167, 69, 0.9);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
    }
    
    .stock-low {
        background: rgba(220, 53, 69, 0.9);
    }
    
    .price-tag {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 14px;
    }
    
    .medicine-type-badge {
        background: rgba(108, 117, 125, 0.8);
        color: white;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 10px;
        position: absolute;
        top: 10px;
        left: 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Display Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-white mb-0">💊 Sistem Penjualan Obat</h2>
            <p class="text-muted">Pilih obat dan kelola transaksi penjualan dengan mudah</p>
        </div>
    </div>
    
    <div class="row">
        {{-- Kolom Kiri: Daftar Produk --}}
        <div class="col-lg-7 col-md-7">
            <div class="card bg-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title text-white mb-0">🔍 Daftar Obat</h4>
                        <div class="d-flex align-items-center">
                            <button type="button" id="toggle-modal-mode" class="btn btn-outline-warning btn-sm me-2" 
                                    title="Toggle Modal Konfirmasi - ON: Tampilkan konfirmasi sebelum menambah ke keranjang, OFF: Langsung tambah ke keranjang">
                                <i class="mdi mdi-checkbox-marked-circle"></i> Modal: <span id="modal-status">ON</span>
                            </button>
                            <a href="{{ route('penjualans.riwayat') }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-history"></i> Riwayat Penjualan
                            </a>
                        </div>
                    </div>
                    
                    {{-- Konten Produk --}}
                    <div class="product-grid">
                        <div class="row">
                            @forelse($obatsByJenis->flatten() as $obat)
                                <div class="col-lg-4 col-md-6 col-sm-6 mb-4 product-item">
                                    <div class="card product-card position-relative" 
                                         data-id="{{ $obat->id }}" 
                                         data-kode="{{ $obat->kode_obat }}"
                                         data-nama="{{ $obat->nama }}" 
                                         data-harga="{{ $obat->harga_jual }}" 
                                         data-stok="{{ $obat->stok }}">
                                        
                                        <div class="medicine-type-badge">{{ $obat->jenis }}</div>
                                        <div class="stock-badge {{ $obat->stok <= 5 ? 'stock-low' : '' }}">
                                            {{ $obat->stok }} pcs
                                        </div>
                                        
                                        <div class="product-image" style="position: relative;">
                                            @if($obat->gambar && file_exists(public_path('storage/' . $obat->gambar)))
                                                <img src="{{ asset('storage/' . $obat->gambar) }}" 
                                                     alt="{{ $obat->nama }}" class="product-image" 
                                                     style="cursor: pointer; z-index: 3;">
                                            @else
                                                <i class="fas fa-pills" style="z-index: 3;"></i>
                                            @endif
                                        </div>
                                        
                                        <div class="card-body text-center p-3">
                                            <h6 class="card-title mb-2 text-white" style="min-height: 45px; font-size: 13px;">
                                                {{ Str::limit($obat->nama, 35) }}
                                            </h6>
                                            <div class="mb-2">
                                                <span class="price-tag">
                                                    Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <small class="text-muted">Kode: {{ $obat->kode_obat }}</small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <p>Tidak ada obat yang tersedia</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Keranjang & Transaksi --}}
        <div class="col-lg-5 col-md-5">
            <div class="card cart-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title text-white mb-0">🛒 Keranjang Belanja</h4>
                        <button type="button" class="btn btn-outline-warning btn-sm" id="clear-cart">
                            🗑️ Kosongkan
                        </button>
                    </div>
                    
                    <form action="{{ route('penjualans.store') }}" method="POST" id="form-penjualan">
                        @csrf
                        <div class="cart-items mb-3">
                            <div class="table-responsive">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-right">Subtotal</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="keranjang">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-shopping-cart fa-2x mb-2"></i><br>
                                                Keranjang kosong.<br>
                                                <small>Klik pada produk untuk menambahkan ke keranjang</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="border-top pt-3">
                            <div class="row mb-2">
                                <div class="col-6"><strong class="text-white">Total Item:</strong></div>
                                <div class="col-6 text-right"><span id="total-items" class="text-info">0</span></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6"><strong class="text-white">TOTAL HARGA:</strong></div>
                                <div class="col-6 text-right">
                                    <h5 id="total-harga" class="text-success mb-0">Rp 0</h5>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block btn-lg" id="process-transaction" disabled>
                                💳 PROSES TRANSAKSI
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- Transaksi Terakhir --}}
            <div class="card mt-4 bg-dark">
                <div class="card-body">
                    <h5 class="card-title text-white">📋 Transaksi Terakhir</h5>
                    <div class="list-group list-group-flush">
                        @forelse($recentSales as $sale)
                            <div class="list-group-item bg-transparent border-secondary mb-2" style="border-radius: 8px;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <a href="{{ route('penjualans.show', $sale->nota) }}" class="text-primary text-decoration-none fw-bold">
                                            {{ $sale->nota }}
                                        </a>
                                        <br><small class="text-muted">{{ \Carbon\Carbon::parse($sale->tanggal_nota)->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="text-right">
                                        <span class="badge badge-primary badge-pill">
                                            {{ $sale->details->count() }} Item{{ $sale->details->count() > 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                </div>
                                
                                @if($sale->details && $sale->details->count() > 0)
                                    <div class="mt-2">
                                        <small class="text-white-50">Obat yang dibeli:</small>
                                        <div class="mt-1">
                                            @foreach($sale->details->take(3) as $index => $detail)
                                                @if($detail->obat)
                                                    <span class="badge badge-outline-info me-1 mb-1" style="background: rgba(0,123,255,0.1); color: #17a2b8; border: 1px solid #17a2b8;">
                                                        <i class="fas fa-pills"></i> {{ $detail->obat->nama }} ({{ $detail->jumlah }}x)
                                                    </span>
                                                @endif
                                            @endforeach
                                            @if($sale->details->count() > 3)
                                                <small class="text-muted">...dan {{ $sale->details->count() - 3 }} obat lainnya</small>
                                            @endif
                                        </div>
                                        
                                        @if($sale->details->where('obat', '!=', null)->count() > 0)
                                            @php
                                                $totalAmount = $sale->details->sum(function($detail) {
                                                    return $detail->obat ? $detail->obat->harga_jual * $detail->jumlah : 0;
                                                });
                                            @endphp
                                            <div class="mt-2">
                                                <small class="text-success fw-bold">
                                                    <i class="fas fa-money-bill-wave"></i> Total: Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Detail transaksi tidak ditemukan
                                    </small>
                                @endif
                            </div>
                        @empty
                            <div class="list-group-item bg-transparent border-0">
                                <div class="text-center text-muted">
                                    <i class="fas fa-receipt fa-2x mb-2"></i><br>
                                    Belum ada transaksi
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
<script>
// Enhanced JavaScript for better cart functionality
window.addEventListener('load', function () {
    $(document).ready(function() {
        let cartItems = [];
        let modalMode = true; // Enable modal mode by default

        // Toggle modal mode
        $('#toggle-modal-mode').click(function() {
            modalMode = !modalMode;
            updateModalToggle();
        });
        
        function updateModalToggle() {
            const button = $('#toggle-modal-mode');
            const status = $('#modal-status');
            
            if (modalMode) {
                button.removeClass('btn-outline-success').addClass('btn-outline-warning');
                button.find('i').removeClass('mdi-checkbox-blank-circle-outline').addClass('mdi-checkbox-marked-circle');
                status.text('ON');
            } else {
                button.removeClass('btn-outline-warning').addClass('btn-outline-success');
                button.find('i').removeClass('mdi-checkbox-marked-circle').addClass('mdi-checkbox-blank-circle-outline');
                status.text('OFF');
            }
        }
        
        // Function to add item to cart from modal
        window.addToCartFromModal = function(medicineData, quantity) {
            const existingItem = cartItems.find(item => item.id === medicineData.id);
            
            if (existingItem) {
                const newQuantity = existingItem.jumlah + quantity;
                if (newQuantity <= medicineData.stok) {
                    existingItem.jumlah = newQuantity;
                    showNotification(`✅ ${medicineData.nama} ditambahkan ke keranjang (${existingItem.jumlah} pcs)`, 'success');
                } else {
                    showNotification(`⚠️ Stok ${medicineData.nama} tidak mencukupi! (Max: ${medicineData.stok} pcs)`, 'warning');
                    return;
                }
            } else {
                medicineData.jumlah = quantity;
                cartItems.push(medicineData);
                showNotification(`✅ ${medicineData.nama} ditambahkan ke keranjang (${quantity} pcs)`, 'success');
            }
            
            renderCart();
        };

        // Add to cart functionality with loading state
        $(document).on('click', '.product-card', function(e) {
            const $card = $(this);
            
            // Prevent double clicks during processing
            if ($card.hasClass('loading')) {
                return;
            }
            
            // Check if clicked on image - handle image click separately
            if ($(e.target).closest('.product-image').length > 0) {
                e.stopPropagation(); // Prevent cart addition when clicking on image
                handleImageClick($card, e);
                return;
            }
            
            // Add loading state
            $card.addClass('loading');
            
            const itemData = {
                id: $card.data('id'),
                kode: $card.data('kode'),
                nama: $card.data('nama'),
                harga: parseFloat($card.data('harga')),
                stok: parseInt($card.data('stok')),
            };

            // Remove loading state immediately for modal mode
            $card.removeClass('loading');
            
            // Check if modal mode is enabled
            if (modalMode) {
                // Show order modal
                const medicineData = {
                    ...itemData,
                    jenis: $card.closest('.product-item').data('type') || '',
                    gambar: $card.find('img').length > 0 ? $card.find('img').attr('src') : ''
                };
                showOrderModal(medicineData);
                return;
            }

            // Direct add mode (original behavior)
            $card.addClass('loading');
            
            // Simulate brief loading for better UX
            setTimeout(() => {
                if (itemData.stok <= 0) {
                    $card.removeClass('loading');
                    showNotification('❌ Stok obat tidak tersedia!', 'danger');
                    return;
                }

                const existingItem = cartItems.find(item => item.id === itemData.id);
                
                if (existingItem) {
                    if (existingItem.jumlah < itemData.stok) {
                        existingItem.jumlah++;
                        showNotification(`✅ ${itemData.nama} ditambahkan ke keranjang (${existingItem.jumlah} pcs)`, 'success');
                    } else {
                        showNotification(`⚠️ Stok ${itemData.nama} tidak mencukupi! (Max: ${itemData.stok} pcs)`, 'warning');
                    }
                } else {
                    itemData.jumlah = 1;
                    cartItems.push(itemData);
                    showNotification(`✅ ${itemData.nama} ditambahkan ke keranjang`, 'success');
                }
                
                renderCart();
                $card.removeClass('loading');
            }, 300);
        });

        // Change quantity with plus/minus buttons
        $(document).on('click', '.increase-qty', function() {
            const itemId = $(this).data('id');
            const item = cartItems.find(item => item.id === itemId);
            
            if (item && item.jumlah < item.stok) {
                item.jumlah++;
                renderCart();
            } else if (item) {
                showNotification(`⚠️ Stok ${item.nama} tidak mencukupi`);
            }
        });

        $(document).on('click', '.decrease-qty', function() {
            const itemId = $(this).data('id');
            const item = cartItems.find(item => item.id === itemId);
            
            if (item && item.jumlah > 1) {
                item.jumlah--;
                renderCart();
            } else if (item && item.jumlah === 1) {
                showNotification(`⚠️ Jumlah minimal adalah 1. Gunakan tombol hapus untuk menghapus item.`);
            }
        });

        // Remove item
        $(document).on('click', '.hapus-item', function() {
            const itemId = $(this).data('id');
            const item = cartItems.find(item => item.id === itemId);
            cartItems = cartItems.filter(item => item.id !== itemId);
            if (item) {
                showNotification(`🗑️ ${item.nama} dihapus dari keranjang`);
            }
            renderCart();
        });

        // Clear cart
        $('#clear-cart').click(function() {
            if (cartItems.length > 0) {
                if (confirm('🗑️ Yakin ingin mengosongkan keranjang?')) {
                    cartItems = [];
                    renderCart();
                    showNotification('🗑️ Keranjang dikosongkan');
                }
            }
        });

        // Render cart
        function renderCart() {
            const keranjangBody = $('#keranjang');
            const form = $('#form-penjualan');
            const processBtn = $('#process-transaction');
            
            keranjangBody.empty();
            
            let grandTotal = 0;
            let totalItems = 0;

            // Remove old hidden inputs
            form.find('input[name^="items"]').remove();

            if (cartItems.length === 0) {
                keranjangBody.html(`
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i><br>
                            Keranjang kosong.<br>
                            <small>Klik pada produk untuk menambahkan ke keranjang</small>
                        </td>
                    </tr>
                `);
                processBtn.prop('disabled', true);
            } else {
                cartItems.forEach((item, index) => {
                    const subtotal = item.harga * item.jumlah;
                    grandTotal += subtotal;
                    totalItems += item.jumlah;
                    
                    const row = `
                        <tr>
                            <td>
                                <strong class="text-white">${item.nama}</strong><br>
                                <small class="text-muted">Kode: ${item.kode}</small><br>
                                <small class="text-success">${formatRupiah(item.harga)}/pcs</small>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn btn-outline-secondary btn-sm me-1 decrease-qty" data-id="${item.id}" style="width: 25px; height: 25px; padding: 0;">-</button>
                                    <span class="text-white font-weight-bold mx-2" id="qty-${item.id}">${item.jumlah}</span>
                                    <button type="button" class="btn btn-outline-secondary btn-sm ms-1 increase-qty" data-id="${item.id}" style="width: 25px; height: 25px; padding: 0;">+</button>
                                </div>
                                <small class="text-muted">max: ${item.stok}</small>
                            </td>
                            <td class="text-right">
                                <strong class="text-success">${formatRupiah(subtotal)}</strong>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm hapus-item" data-id="${item.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    keranjangBody.append(row);
                    
                    // Add hidden inputs for form submission
                    form.append(`<input type="hidden" name="items[${index}][kode_obat]" value="${item.kode}">`);
                    form.append(`<input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}">`);
                });
                processBtn.prop('disabled', false);
            }
            
            $('#total-harga').text(formatRupiah(grandTotal));
            $('#total-items').text(totalItems + ' item');
        }

        // Handle image click for viewing
        function handleImageClick($card, e) {
            const imageUrl = $card.find('.product-image img').attr('src');
            const productName = $card.data('nama');
            
            if (imageUrl) {
                // Create image modal
                const modal = $(`
                    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content bg-dark">
                                <div class="modal-header border-secondary">
                                    <h5 class="modal-title text-white">${productName}</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center p-0">
                                    <img src="${imageUrl}" alt="${productName}" 
                                         class="img-fluid" style="max-height: 500px; object-fit: contain;">
                                </div>
                                <div class="modal-footer border-secondary">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="button" class="btn btn-primary" onclick="$(this).closest('.modal').modal('hide'); $('.product-card[data-nama=&quot;${productName}&quot;]').click();">
                                        🛒 Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                
                // Remove existing modal if any
                $('#imageModal').remove();
                
                // Add modal to body and show
                $('body').append(modal);
                $('#imageModal').modal('show');
                
                // Remove modal when hidden
                $('#imageModal').on('hidden.bs.modal', function() {
                    $(this).remove();
                });
            }
        }

        // Format currency
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR', 
                minimumFractionDigits: 0 
            }).format(angka);
        }

        // Show notification with type
        function showNotification(message, type = 'info') {
            // Mapping type to Bootstrap alert classes
            const alertClass = {
                'success': 'alert-success',
                'danger': 'alert-danger', 
                'warning': 'alert-warning',
                'info': 'alert-info'
            };
            
            const notification = $(`
                <div class="alert ${alertClass[type]} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; max-width: 350px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                    <strong>${message}</strong>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            `);
            
            $('body').append(notification);
            
            // Auto dismiss notification
            setTimeout(() => {
                notification.alert('close');
            }, type === 'success' ? 2000 : 4000);
        }

        // Form submission validation
        $('#form-penjualan').on('submit', function(e) {
            console.log('Form submitted, cart items:', cartItems.length);
            
            if (cartItems.length === 0) {
                e.preventDefault();
                showNotification('❌ Keranjang masih kosong!', 'danger');
                return false;
            }
            
            const totalAmount = cartItems.reduce((total, item) => total + (item.harga * item.jumlah), 0);
            
            // Prevent default form submission
            e.preventDefault();
            
            // Show transaction confirmation modal
            showTransactionModal(this, cartItems, totalAmount);
            
            return false;
        });
    });
});
</script>
@endpush