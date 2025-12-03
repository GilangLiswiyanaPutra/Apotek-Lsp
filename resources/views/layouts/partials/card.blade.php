<div class="col-lg-4 col-md-6 col-sm-6 mb-3">
    <div class="card product-card bg-dark" 
         data-id="{{ $obat->id }}" 
         data-kode="{{ $obat->kode_obat }}"
         data-nama="{{ $obat->nama }}" 
         data-harga="{{ $obat->harga_jual }}" 
         data-stok="{{ $obat->stok }}">
        <div class="card-body text-center p-3">
            <h6 class="card-title mb-1" style="min-height: 40px;">{{ Str::limit($obat->nama, 30) }}</h6>
            <p class="text-muted small mb-2">Stok: {{ $obat->stok }}</p>
            <p class="font-weight-bold text-success mb-0">Rp {{ number_format($obat->harga_jual) }}</p>
        </div>
    </div>
</div>
