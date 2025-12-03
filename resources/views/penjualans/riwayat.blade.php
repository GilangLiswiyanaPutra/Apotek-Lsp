@extends('layouts.app')
@section('title', 'Riwayat Penjualan')

@push('styles')
<style>
    .stats-card {
        background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
        border: 2px solid #404040;
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        border-color: #007bff;
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
    }
    
    .stats-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }
    
    .transaction-card {
        background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
        border: 2px solid #404040;
        border-radius: 12px;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    
    .transaction-card:hover {
        border-color: #28a745;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
    }
    
    .medicine-item {
        background: rgba(0, 123, 255, 0.1);
        border: 1px solid rgba(0, 123, 255, 0.3);
        border-radius: 8px;
        padding: 8px 12px;
        margin: 5px;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .medicine-item:hover {
        background: rgba(0, 123, 255, 0.2);
        transform: translateX(5px);
    }
    

    
    .top-medicine-item {
        background: linear-gradient(45deg, #28a745, #20c997);
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .top-medicine-item:hover {
        transform: translateX(10px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    
    .pagination .page-link {
        background-color: #2a2a2a;
        border-color: #404040;
        color: #fff;
    }
    
    .pagination .page-link:hover {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-white mb-0">📊 Riwayat Penjualan</h2>
            <p class="text-muted">Laporan lengkap penjualan obat dan statistik toko</p>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card text-center">
                <div class="card-body">
                    <div class="stats-icon text-primary">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h3 class="text-white">{{ number_format($totalTransaksi) }}</h3>
                    <p class="text-muted mb-0">Total Transaksi</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card text-center">
                <div class="card-body">
                    <div class="stats-icon text-success">
                        <i class="fas fa-pills"></i>
                    </div>
                    <h3 class="text-white">{{ number_format($totalObatTerjual) }}</h3>
                    <p class="text-muted mb-0">Obat Terjual</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card text-center">
                <div class="card-body">
                    <div class="stats-icon text-info">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <h3 class="text-white">{{ \Carbon\Carbon::today()->format('d/m/Y') }}</h3>
                    <p class="text-muted mb-0">Hari Ini</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card text-center">
                <div class="card-body">
                    <div class="stats-icon text-warning">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-white">{{ $riwayatPenjualan->count() }}</h3>
                    <p class="text-muted mb-0">Transaksi Periode Ini</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Main Content: Transaction History --}}
        <div class="col-lg-8">
            {{-- Transaction List --}}
            <div id="transaction-list">
                @forelse($riwayatPenjualan as $penjualan)
                    <div class="transaction-card" data-nota="{{ $penjualan->nota }}" 
                         data-date="{{ $penjualan->tanggal_nota }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-2" style="display: none;" id="bulk-checkbox-{{ $penjualan->nota }}">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input transaction-checkbox" 
                                               id="checkbox-{{ $penjualan->nota }}" value="{{ $penjualan->nota }}"
                                               onchange="updateBulkDeleteButton()">
                                        <label class="custom-control-label text-white" for="checkbox-{{ $penjualan->nota }}">
                                            <strong>Pilih untuk dihapus</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="text-primary mb-1">
                                                <i class="fas fa-receipt"></i> {{ $penjualan->nota }}
                                            </h5>
                                            <p class="text-muted mb-1">
                                                <i class="fas fa-calendar"></i> 
                                                {{ \Carbon\Carbon::parse($penjualan->tanggal_nota)->format('d F Y, H:i') }}
                                            </p>
                                            @if($penjualan->user)
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-user"></i> 
                                                    Kasir: <span class="cashier-name">{{ $penjualan->user->name ?? 'System' }}</span>
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span class="badge badge-success badge-lg">
                                                {{ $penjualan->details->count() }} Item{{ $penjualan->details->count() > 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Medicine Details --}}
                                    <div class="mb-3">
                                        <h6 class="text-white mb-2">💊 Obat yang Dibeli:</h6>
                                        <div class="medicine-list" style="display: flex; flex-direction: column; gap: 10px;">
                                            @foreach($penjualan->details as $detail)
                                                @if($detail->obat)
                                                    <div class="medicine-item d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <strong class="medicine-name">{{ $detail->obat->nama }}</strong>
                                                            <br>
                                                            <small class="text-muted medicine-details">
                                                                <span class="medicine-code">Kode: {{ $detail->kode_obat }}</span> | 
                                                                <span class="medicine-quantity">Qty: {{ $detail->jumlah }} pcs</span> | 
                                                                <span class="medicine-price">Rp {{ number_format($detail->obat->harga_jual, 0, ',', '.') }}</span>
                                                            </small>
                                                            <br>
                                                            <small class="text-success medicine-subtotal">
                                                                Subtotal: Rp {{ number_format($detail->obat->harga_jual * $detail->jumlah, 0, ',', '.') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="medicine-item border-warning d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <strong class="text-warning">
                                                                <i class="fas fa-exclamation-triangle"></i> Obat Tidak Ditemukan
                                                            </strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                Kode: {{ $detail->kode_obat }} | Qty: {{ $detail->jumlah }} pcs
                                                            </small>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Total Amount --}}
                                    @php
                                        $totalAmount = $penjualan->details->sum(function($detail) {
                                            return $detail->obat ? $detail->obat->harga_jual * $detail->jumlah : 0;
                                        });
                                    @endphp
                                    <div class="border-top pt-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <strong class="text-white">TOTAL TRANSAKSI:</strong>
                                            </div>
                                            <div class="col-6 text-right">
                                                <h5 class="text-success mb-0">
                                                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-shopping-cart text-primary" style="font-size: 3rem;"></i>
                                    </div>
                                    <a href="{{ route('penjualans.show', $penjualan->nota) }}" 
                                       class="btn btn-outline-primary btn-sm mb-2 btn-block">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                    <button class="btn btn-outline-info btn-sm mb-2 btn-block" 
                                            onclick="printTransaction('{{ $penjualan->nota }}')">
                                        <i class="fas fa-print"></i> Print Struk
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm btn-block" 
                                            onclick="confirmDeleteTransaction('{{ $penjualan->nota }}')"
                                            title="Hapus transaksi ini (stok obat akan dikembalikan)">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="transaction-card text-center">
                        <div class="card-body py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum Ada Transaksi</h4>
                            <p class="text-muted">Transaksi penjualan akan muncul di sini setelah ada penjualan obat.</p>
                            <a href="{{ route('penjualans.index') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Mulai Transaksi
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($riwayatPenjualan->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $riwayatPenjualan->links() }}
                </div>
            @endif
        </div>

        {{-- Sidebar: Top Medicines --}}
        <div class="col-lg-4">
            <div class="card stats-card">
                <div class="card-body">
                    <h5 class="card-title text-white mb-4">
                        <i class="fas fa-trophy text-warning"></i> Obat Terlaris
                    </h5>
                    
                    @forelse($obatTerlaris as $index => $obat)
                        <div class="top-medicine-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <!-- <span class="badge badge-light badge-pill me-2">
                                            #{{ $index + 1 }}
                                        </span> -->
                                        <div>
                                            <strong class="text-white">
                                                {{ $obat->obat ? $obat->obat->nama : 'Obat Tidak Ditemukan' }}
                                            </strong>
                                            <br>
                                            <small class="text-light">
                                                Kode: {{ $obat->kode_obat }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-warning badge-pill">
                                        {{ $obat->total_terjual }} pcs
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <p>Belum ada data penjualan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card stats-card mt-4">
                <div class="card-body">
                    <h5 class="card-title text-white mb-4">
                        <i class="fas fa-bolt text-warning"></i> Aksi Cepat
                    </h5>
                    
                    <div class="alert alert-info mb-3" style="background: rgba(0,123,255,0.1); border: 1px solid rgba(0,123,255,0.3);">
                        <small class="text-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Info:</strong> Saat menghapus transaksi, stok obat akan otomatis dikembalikan.
                        </small>
                    </div>
                    
                    <a href="{{ route('penjualans.index') }}" class="btn btn-primary btn-block mb-3">
                        <i class="fas fa-plus"></i> Transaksi Baru
                    </a>
                    
                    <!-- <button class="btn btn-outline-success btn-block mb-3" onclick="exportData()">
                        <i class="fas fa-download"></i> Export Data
                    </button> -->
                    
                    <button class="btn btn-outline-info btn-block mb-3" onclick="refreshData()">
                        <i class="fas fa-sync"></i> Refresh Data
                    </button>
                    
                    <!-- <button class="btn btn-outline-danger btn-block" onclick="toggleBulkDelete()" id="bulk-delete-toggle"
                            title="Aktifkan mode hapus multiple untuk menghapus beberapa transaksi sekaligus">
                        <i class="fas fa-tasks"></i> Hapus Multiple
                    </button> -->
                    
                    <div id="bulk-delete-controls" class="mt-3" style="display: none;">
                        <button class="btn btn-danger btn-block mb-2" onclick="confirmBulkDelete()" id="bulk-delete-btn" disabled>
                            <i class="fas fa-trash"></i> Hapus Terpilih (<span id="selected-count">0</span>)
                        </button>
                        <button class="btn btn-outline-secondary btn-block" onclick="cancelBulkDelete()">
                            <i class="fas fa-times"></i> Batal
                        </button>
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
// Print transaction function
function printTransaction(nota) {
    window.open(`{{ route('penjualans.show', '') }}/${nota}?print=1`, '_blank');
}

// Export data function
function exportData() {
    alert('📊 Fitur export akan segera tersedia!');
}

// Refresh data function
function refreshData() {
    location.reload();
}



// Show notification
function showNotification(message, type = 'info') {
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
    
    setTimeout(() => {
        notification.alert('close');
    }, 3000);
}

// Delete transaction functions
function confirmDeleteTransaction(nota) {
    Swal.fire({
        title: '⚠️ Konfirmasi Hapus',
        html: `Apakah Anda yakin ingin menghapus transaksi <strong>${nota}</strong>?<br><br>
               <small class="text-muted">Stok obat akan dikembalikan setelah penghapusan.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times"></i> Batal',
        background: '#2a2a2a',
        color: '#fff',
        customClass: {
            popup: 'border border-secondary'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            deleteTransaction(nota);
        }
    });
}

function deleteTransaction(nota) {
    // Show loading
    Swal.fire({
        title: 'Menghapus Transaksi...',
        text: 'Mohon tunggu, sedang memproses penghapusan.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        },
        background: '#2a2a2a',
        color: '#fff'
    });

    $.ajax({
        url: `{{ route('penjualans.destroy', ':nota') }}`.replace(':nota', nota),
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: '✅ Berhasil!',
                    text: response.message,
                    icon: 'success',
                    background: '#2a2a2a',
                    color: '#fff',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    // Remove the transaction card from view or reload page
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: '❌ Gagal!',
                    text: response.message,
                    icon: 'error',
                    background: '#2a2a2a',
                    color: '#fff',
                    confirmButtonColor: '#dc3545'
                });
            }
        },
        error: function(xhr) {
            let errorMessage = 'Terjadi kesalahan saat menghapus transaksi.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            Swal.fire({
                title: '❌ Error!',
                text: errorMessage,
                icon: 'error',
                background: '#2a2a2a',
                color: '#fff',
                confirmButtonColor: '#dc3545'
            });
        }
    });
}

// Bulk delete functions
function toggleBulkDelete() {
    const isActive = $('#bulk-delete-controls').is(':visible');
    
    if (isActive) {
        cancelBulkDelete();
    } else {
        // Show checkboxes
        $('[id^="bulk-checkbox-"]').fadeIn(200);
        $('#bulk-delete-controls').fadeIn(200);
        $('#bulk-delete-toggle').html('<i class="fas fa-times"></i> Batal Mode Hapus');
        
        showNotification('📋 Mode hapus multiple diaktifkan. Pilih transaksi yang ingin dihapus.', 'info');
    }
}

function cancelBulkDelete() {
    // Hide checkboxes
    $('[id^="bulk-checkbox-"]').fadeOut(200);
    $('#bulk-delete-controls').fadeOut(200);
    $('#bulk-delete-toggle').html('<i class="fas fa-tasks"></i> Hapus Multiple');
    
    // Uncheck all checkboxes
    $('.transaction-checkbox').prop('checked', false);
    updateBulkDeleteButton();
}

function updateBulkDeleteButton() {
    const checkedCount = $('.transaction-checkbox:checked').length;
    $('#selected-count').text(checkedCount);
    $('#bulk-delete-btn').prop('disabled', checkedCount === 0);
}

function confirmBulkDelete() {
    const selectedTransactions = $('.transaction-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedTransactions.length === 0) {
        showNotification('❗ Pilih minimal satu transaksi untuk dihapus.', 'warning');
        return;
    }
    
    Swal.fire({
        title: '⚠️ Konfirmasi Hapus Multiple',
        html: `Apakah Anda yakin ingin menghapus <strong>${selectedTransactions.length}</strong> transaksi yang dipilih?<br><br>
               <small class="text-muted">Stok obat akan dikembalikan untuk semua transaksi yang dihapus.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus Semua!',
        cancelButtonText: '<i class="fas fa-times"></i> Batal',
        background: '#2a2a2a',
        color: '#fff',
        customClass: {
            popup: 'border border-secondary'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            bulkDeleteTransactions(selectedTransactions);
        }
    });
}

function bulkDeleteTransactions(notaList) {
    // Show loading
    Swal.fire({
        title: 'Menghapus Transaksi...',
        text: `Mohon tunggu, sedang menghapus ${notaList.length} transaksi.`,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        },
        background: '#2a2a2a',
        color: '#fff'
    });

    $.ajax({
        url: '{{ route("penjualans.bulkDestroy") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            nota_list: notaList
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: '✅ Berhasil!',
                    text: response.message,
                    icon: 'success',
                    background: '#2a2a2a',
                    color: '#fff',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    // Reload page to show updated data
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: '❌ Gagal!',
                    text: response.message,
                    icon: 'error',
                    background: '#2a2a2a',
                    color: '#fff',
                    confirmButtonColor: '#dc3545'
                });
            }
        },
        error: function(xhr) {
            let errorMessage = 'Terjadi kesalahan saat menghapus transaksi.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            Swal.fire({
                title: '❌ Error!',
                text: errorMessage,
                icon: 'error',
                background: '#2a2a2a',
                color: '#fff',
                confirmButtonColor: '#dc3545'
            });
        }
    });
}
</script>
@endpush