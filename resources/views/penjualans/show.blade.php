@extends('layouts.app')
@section('title', 'Detail Transaksi')

@push('styles')
<style>
    .receipt-container {
        background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
        border: 2px solid #404040;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        overflow: hidden;
    }
    
    .receipt-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        padding: 25px;
        text-align: center;
        position: relative;
    }
    
    .receipt-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #28a745, #007bff, #6f42c1, #fd7e14);
    }
    
    .receipt-body {
        padding: 30px;
        background: #1a1a1a;
        color: #e0e0e0;
    }
    
    .medicine-item {
        background: linear-gradient(145deg, #333333, #2a2a2a);
        border: 1px solid #404040;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .medicine-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #007bff, #28a745);
    }
    
    .medicine-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        border-color: #007bff;
    }
    
    .medicine-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .medicine-name {
        font-size: 1.2rem;
        font-weight: bold;
        color: #007bff;
        margin: 0;
    }
    
    .medicine-code {
        background: #007bff;
        color: white;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    
    .medicine-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    
    .detail-item {
        text-align: center;
        padding: 10px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .detail-label {
        font-size: 0.8rem;
        color: #888;
        display: block;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .detail-value {
        font-size: 1rem;
        font-weight: bold;
        color: #e0e0e0;
    }
    
    .price-highlight {
        color: #28a745;
    }
    
    .quantity-highlight {
        color: #ffc107;
    }
    
    .total-section {
        background: linear-gradient(145deg, #404040, #2a2a2a);
        border-radius: 10px;
        padding: 25px;
        margin-top: 30px;
        border: 2px solid #007bff;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .total-row:last-child {
        border-bottom: none;
        font-size: 1.3rem;
        font-weight: bold;
        color: #28a745;
        margin-top: 15px;
        padding-top: 20px;
        border-top: 2px solid #28a745;
    }
    
    .btn-back {
        background: linear-gradient(135deg, #6c757d, #495057);
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-back:hover {
        background: linear-gradient(135deg, #495057, #343a40);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-print {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        color: white;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-print:hover {
        background: linear-gradient(135deg, #20c997, #17a2b8);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    
    .transaction-info {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .info-item {
        text-align: center;
    }
    
    .info-label {
        font-size: 0.9rem;
        color: #888;
        display: block;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        font-size: 1.1rem;
        font-weight: bold;
        color: #007bff;
    }

    @media print {
        .btn-back, .btn-print {
            display: none;
        }
        
        .receipt-container {
            box-shadow: none;
            border: 1px solid #000;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="receipt-container">
                <!-- Header -->
                <div class="receipt-header">
                    <h1 class="mb-3">
                        <i class="fas fa-receipt"></i>
                        Detail Transaksi Penjualan
                    </h1>
                    <h2 class="mb-0">{{ $penjualan->nota }}</h2>
                    <p class="mb-0 mt-2 opacity-75">Apotek Management System</p>
                </div>
                
                <!-- Body -->
                <div class="receipt-body">
                    <!-- Transaction Info -->
                    <div class="transaction-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Nomor Nota</span>
                                <span class="info-value">{{ $penjualan->nota }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Tanggal Transaksi</span>
                                <span class="info-value">{{ \Carbon\Carbon::parse($penjualan->tanggal_nota)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Total Item</span>
                                <span class="info-value">{{ $penjualan->details->count() }} Jenis Obat</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Total Quantity</span>
                                <span class="info-value">{{ $penjualan->details->sum('jumlah') }} Item</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Medicine Details -->
                    <h3 class="mb-4 text-center" style="color: #007bff;">
                        <i class="fas fa-pills"></i>
                        Detail Obat yang Dibeli
                    </h3>
                    
                    @if($penjualan->details && $penjualan->details->count() > 0)
                        @foreach($penjualan->details as $index => $detail)
                            <div class="medicine-item">
                                <div class="medicine-header">
                                    <h4 class="medicine-name">
                                        <i class="fas fa-capsules"></i>
                                        {{ $detail->obat->nama ?? 'Obat Tidak Ditemukan' }}
                                    </h4>
                                    <span class="medicine-code">{{ $detail->kode_obat }}</span>
                                </div>
                                
                                @if($detail->obat)
                                    <div class="medicine-details">
                                        <div class="detail-item">
                                            <span class="detail-label">Jenis Obat</span>
                                            <span class="detail-value">{{ $detail->obat->jenis }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Satuan</span>
                                            <span class="detail-value">{{ $detail->obat->satuan }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Harga Satuan</span>
                                            <span class="detail-value price-highlight">
                                                Rp {{ number_format($detail->obat->harga_jual, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Jumlah Beli</span>
                                            <span class="detail-value quantity-highlight">{{ $detail->jumlah }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Subtotal</span>
                                            <span class="detail-value price-highlight">
                                                Rp {{ number_format($detail->obat->harga_jual * $detail->jumlah, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Supplier</span>
                                            <span class="detail-value">{{ $detail->obat->supplier }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Data obat tidak ditemukan untuk kode: {{ $detail->kode_obat }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        
                        <!-- Total Section -->
                        <div class="total-section">
                            <h4 class="text-center mb-4" style="color: #007bff;">
                                <i class="fas fa-calculator"></i>
                                Ringkasan Pembayaran
                            </h4>
                            
                            @php
                                $totalItems = $penjualan->details->count();
                                $totalQuantity = $penjualan->details->sum('jumlah');
                                $totalAmount = $penjualan->details->sum(function($detail) {
                                    return $detail->obat ? $detail->obat->harga_jual * $detail->jumlah : 0;
                                });
                            @endphp
                            
                            <div class="total-row">
                                <span>Total Jenis Obat:</span>
                                <span>{{ $totalItems }} Jenis</span>
                            </div>
                            <div class="total-row">
                                <span>Total Quantity:</span>
                                <span>{{ $totalQuantity }} Item</span>
                            </div>
                            <div class="total-row">
                                <span>TOTAL PEMBAYARAN:</span>
                                <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h4>Tidak Ada Detail Transaksi</h4>
                            <p>Transaksi ini tidak memiliki detail pembelian obat.</p>
                        </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid rgba(255,255,255,0.1);">
                        <a href="{{ route('penjualans.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left"></i>
                            Kembali ke Transaksi
                        </a>
                        
                        <button onclick="window.print()" class="btn-print">
                            <i class="fas fa-print"></i>
                            Cetak Struk
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto focus on print when page loads (optional)
    document.addEventListener('DOMContentLoaded', function() {
        // You can add any additional JavaScript here if needed
        console.log('Detail transaksi loaded successfully');
    });
    
    // Enhanced print functionality
    function printReceipt() {
        // Hide non-printable elements
        const nonPrintable = document.querySelectorAll('.btn-back, .btn-print');
        nonPrintable.forEach(el => el.style.display = 'none');
        
        // Print
        window.print();
        
        // Restore elements after print
        setTimeout(() => {
            nonPrintable.forEach(el => el.style.display = '');
        }, 1000);
    }
</script>
@endpush