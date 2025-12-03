@extends('layouts.app')

@section('title', 'Manajemen Data Obat')

@push('styles')
<style>
    .clickable-row { cursor: pointer; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Obat</h4>
                <p class="card-description">Daftar obat yang tersedia.</p>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                {{-- [RAPihkan] Tombol Tambah dan Form Filter digabung dalam satu baris --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    {{-- Tombol Tambah Obat --}}
                    <div>
                        <a href="{{ route('obats.create') }}" class="btn btn-primary btn-fw">
                            <i class="mdi mdi-plus"></i> Tambah Obat
                        </a>
                    </div>

                    {{-- Form Filter dan Pencarian yang Sudah Dirapikan --}}
                    <div>
                        <form method="GET" action="{{ route('obats.index') }}" class="d-flex">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama obat..." value="{{ request('search') }}">
                                <select name="jenis" class="form-control">
                                    <option value="">Semua Jenis</option>
                                    @foreach($all_jenis ?? [] as $jenis)
                                        <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                    @endforeach
                                </select>
                                <select name="supplier" class="form-control">
                                    <option value="">Semua Supplier</option>
                                    @foreach($all_suppliers ?? [] as $supplier)
                                        <option value="{{ $supplier }}" {{ request('supplier') == $supplier ? 'selected' : '' }}>{{ $supplier }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i></button>
                                <!-- <a href="{{ route('obats.index') }}" class="btn btn-dark"><i class="mdi mdi-reload"></i></a> -->
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode Obat</th>
                                <th>Nama Obat</th>
                                <th>Jenis</th>
                                <th>Stok</th>
                                <th>Harga Jual</th>
                                <th>Tanggal Kadaluarsa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($obats as $obat)
                                @php
                                    $expirationStatus = $obat->getExpirationStatus();
                                    $rowClass = '';
                                    if ($obat->isExpired()) {
                                        $rowClass = 'table-danger'; // Red background for expired drugs
                                    } elseif ($obat->isNearExpiration()) {
                                        $rowClass = 'table-warning';
                                    }
                                @endphp
                                <tr class="clickable-row {{ $rowClass }}" data-href="{{ route('obats.show', $obat->id) }}">
                                    <td><span class="badge badge-dark">{{ $obat->kode_obat }}</span></td>
                                    <td>
                                        {{ $obat->nama }}
                                        @if($obat->isExpired() || $obat->isNearExpiration())
                                            <i class="mdi mdi-alert-circle text-{{ $obat->isExpired() ? 'danger' : 'warning' }}" title="{{ $expirationStatus['status'] }}"></i>
                                        @endif
                                    </td>
                                    <td><label class="badge badge-gradient-success">{{ $obat->jenis }}</label></td>
                                    <td>{{ $obat->stok }} {{ $obat->satuan }}</td>
                                    <td>Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</td>
                                    <td>
                                        @if($obat->tgl_kadaluarsa)
                                            {{ $obat->tgl_kadaluarsa->format('d/m/Y') }}
                                            @if($obat->getDaysUntilExpiration() !== null)
                                                <br><small class="text-muted">
                                                    @if($obat->isExpired())
                                                        Telah kadaluarsa {{ abs($obat->getDaysUntilExpiration()) }} hari
                                                    @else
                                                        {{ $obat->getDaysUntilExpiration() }} hari lagi
                                                    @endif
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    {{-- [FIX] Mengembalikan Tombol Aksi yang Hilang --}}
                                    <td>
                                        <a href="{{ route('obats.edit', $obat->id) }}" class="btn btn-sm btn-warning"><i class="mdi mdi-pencil"></i></a>
                                        <form action="{{ route('obats.destroy', $obat->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                    data-nama="{{ $obat->nama }}"
                                                    data-message="Yakin hapus obat '{{ $obat->nama }}'? Data akan dihapus permanen.">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Sesuaikan colspan dengan jumlah kolom yang benar (7) --}}
                                    <td colspan="7" class="text-center">Data obat tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- [FIX] Mengembalikan Paginasi yang Hilang --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        @if($obats->total() > 0)
                            Showing {{ $obats->firstItem() }} to {{ $obats->lastItem() }} of {{ $obats->total() }} results
                        @endif
                    </div>
                    <div>
                        {{ $obats->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle clickable rows dengan debugging dan perbaikan
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function(e) {
                // Mencegah navigasi jika yang diklik adalah elemen interaktif di dalam baris
                if (e.target.closest('a, button, form, input, select')) {
                    console.log('Clicked on interactive element, preventing navigation');
                    return;
                }
                
                // Debug: Log untuk memastikan event terpicu
                console.log('Row clicked, data-href:', this.dataset.href);
                
                // Pastikan ada href sebelum navigasi
                if (this.dataset.href) {
                    console.log('Navigating to:', this.dataset.href);
                    window.location.href = this.dataset.href;
                } else {
                    console.error('No href found in data-href attribute');
                }
            });
            
            // Tambahkan visual feedback saat hover
            row.style.cursor = 'pointer';
        });
        
        // Handle delete buttons with modal
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent row click event
                console.log('Delete button clicked');
                const form = this.closest('form');
                const message = this.getAttribute('data-message');
                showDeleteModal(form, message);
            });
        });
        
        // Handle edit buttons - prevent row click when edit button is clicked
        document.querySelectorAll('a[href*="edit"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent row click event
                console.log('Edit button clicked');
            });
        });
        
        // Debug: Log jumlah clickable rows yang ditemukan
        console.log('Found', document.querySelectorAll('.clickable-row').length, 'clickable rows');
    });
</script>
@endpush