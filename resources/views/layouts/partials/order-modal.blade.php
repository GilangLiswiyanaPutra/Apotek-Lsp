{{-- Medicine Order Confirmation Modal --}}
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark border-primary">
            <div class="modal-header border-primary">
                <h5 class="modal-title text-white" id="orderModalLabel">
                    <i class="mdi mdi-cart-plus text-primary"></i> Tambah ke Keranjang
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-white">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="medicine-image-container mb-3">
                            <img id="order-medicine-image" src="" alt="" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;">
                            <div id="order-medicine-icon" class="medicine-icon-placeholder" style="display: none;">
                                <i class="fas fa-pills fa-3x text-primary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-primary mb-2" id="order-medicine-name">Nama Obat</h6>
                        <div class="mb-2">
                            <small class="text-muted">Kode:</small>
                            <span id="order-medicine-code" class="badge badge-dark">-</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Jenis:</small>
                            <span id="order-medicine-type" class="badge badge-info">-</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Harga Satuan:</small>
                            <h6 class="text-success mb-0" id="order-medicine-price">Rp 0</h6>
                        </div>
                        <div class="stock-info mb-3">
                            <small class="text-muted">Stok Tersedia:</small>
                            <span id="order-medicine-stock" class="badge badge-success">0 pcs</span>
                        </div>
                    </div>
                </div>
                
                <hr class="border-secondary">
                
                <div class="row">
                    <div class="col-md-6">
                        <label for="order-quantity" class="form-label">Jumlah:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary" type="button" id="decrease-qty">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                            <input type="number" id="order-quantity" class="form-control text-center" value="1" min="1" max="1">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="increase-qty">
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Total Harga:</label>
                        <h5 id="order-total-price" class="text-success mb-0">Rp 0</h5>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="mdi mdi-information"></i>
                    <small>Obat akan ditambahkan ke keranjang belanja Anda.</small>
                </div>
            </div>
            <div class="modal-footer border-primary">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="mdi mdi-close"></i> Batal
                </button>
                <button type="button" class="btn btn-primary" id="confirmOrderBtn">
                    <i class="mdi mdi-cart-plus"></i> Tambah ke Keranjang
                </button>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Order Modal --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentMedicine = null;
    let unitPrice = 0;
    let maxStock = 0;
    
    // Function to show order modal
    window.showOrderModal = function(medicineData) {
        currentMedicine = medicineData;
        unitPrice = parseFloat(medicineData.harga);
        maxStock = parseInt(medicineData.stok);
        
        // Set medicine information
        document.getElementById('order-medicine-name').textContent = medicineData.nama;
        document.getElementById('order-medicine-code').textContent = medicineData.kode;
        document.getElementById('order-medicine-type').textContent = medicineData.jenis || '-';
        document.getElementById('order-medicine-price').textContent = formatRupiah(unitPrice);
        document.getElementById('order-medicine-stock').textContent = maxStock + ' pcs';
        
        // Set image or icon
        const imageElement = document.getElementById('order-medicine-image');
        const iconElement = document.getElementById('order-medicine-icon');
        
        if (medicineData.gambar && medicineData.gambar !== '') {
            imageElement.src = medicineData.gambar;
            imageElement.style.display = 'block';
            iconElement.style.display = 'none';
        } else {
            imageElement.style.display = 'none';
            iconElement.style.display = 'block';
        }
        
        // Reset quantity
        const quantityInput = document.getElementById('order-quantity');
        quantityInput.value = 1;
        quantityInput.max = maxStock;
        
        // Update total price
        updateTotalPrice();
        
        // Update stock badge color
        const stockBadge = document.getElementById('order-medicine-stock');
        if (maxStock <= 5) {
            stockBadge.className = 'badge badge-warning';
        } else if (maxStock <= 0) {
            stockBadge.className = 'badge badge-danger';
        } else {
            stockBadge.className = 'badge badge-success';
        }
        
        // Disable confirm button if no stock
        document.getElementById('confirmOrderBtn').disabled = maxStock <= 0;
        
        // Show modal
        $('#orderModal').modal('show');
    };
    
    // Function to update total price
    function updateTotalPrice() {
        const quantity = parseInt(document.getElementById('order-quantity').value) || 1;
        const total = unitPrice * quantity;
        document.getElementById('order-total-price').textContent = formatRupiah(total);
    }
    
    // Quantity control handlers
    document.getElementById('decrease-qty').addEventListener('click', function() {
        const quantityInput = document.getElementById('order-quantity');
        const currentQty = parseInt(quantityInput.value);
        if (currentQty > 1) {
            quantityInput.value = currentQty - 1;
            updateTotalPrice();
        }
    });
    
    document.getElementById('increase-qty').addEventListener('click', function() {
        const quantityInput = document.getElementById('order-quantity');
        const currentQty = parseInt(quantityInput.value);
        if (currentQty < maxStock) {
            quantityInput.value = currentQty + 1;
            updateTotalPrice();
        }
    });
    
    // Quantity input change handler
    document.getElementById('order-quantity').addEventListener('input', function() {
        const value = parseInt(this.value);
        if (isNaN(value) || value < 1) {
            this.value = 1;
        } else if (value > maxStock) {
            this.value = maxStock;
        }
        updateTotalPrice();
    });
    
    // Confirm order button handler
    document.getElementById('confirmOrderBtn').addEventListener('click', function() {
        if (currentMedicine && typeof window.addToCartFromModal === 'function') {
            const quantity = parseInt(document.getElementById('order-quantity').value);
            window.addToCartFromModal(currentMedicine, quantity);
            $('#orderModal').modal('hide');
        }
    });
    
    // Reset modal when hidden
    $('#orderModal').on('hidden.bs.modal', function() {
        currentMedicine = null;
        unitPrice = 0;
        maxStock = 0;
    });
    
    // Format rupiah function (fallback if not already defined)
    if (typeof window.formatRupiah === 'undefined') {
        window.formatRupiah = function(angka) {
            return new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR', 
                minimumFractionDigits: 0 
            }).format(angka);
        };
    }
});
</script>