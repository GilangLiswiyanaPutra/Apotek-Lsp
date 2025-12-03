{{-- Transaction Confirmation Modal --}}
<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark border-primary">
            <div class="modal-header border-primary">
                <h5 class="modal-title text-white" id="transactionModalLabel">
                    <i class="mdi mdi-credit-card text-primary"></i> Konfirmasi Transaksi
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-white">
                <div class="text-center mb-3">
                    <i class="mdi mdi-cart-check text-primary" style="font-size: 48px;"></i>
                </div>
                <div class="transaction-summary">
                    <h6 class="text-center mb-3">Ringkasan Transaksi</h6>
                    <div class="transaction-details">
                        <table class="table table-dark table-sm">
                            <thead>
                                <tr>
                                    <th>Obat</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="transaction-items">
                                <!-- Items will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex justify-content-between">
                            <strong>Total Transaksi:</strong>
                            <strong class="text-primary" id="transaction-total">Rp 0</strong>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="mdi mdi-information"></i> 
                    <strong>Pastikan:</strong> Semua data sudah benar sebelum memproses transaksi.
                </div>
            </div>
            <div class="modal-footer border-primary">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="mdi mdi-close"></i> Batal
                </button>
                <button type="button" class="btn btn-primary" id="confirmTransactionBtn">
                    <i class="mdi mdi-credit-card"></i> Ya, Proses Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Transaction Modal --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let transactionForm = null;
    let transactionData = null;
    
    // Function to show transaction modal
    window.showTransactionModal = function(form, items, total) {
        transactionForm = form;
        transactionData = {items, total};
        
        // Populate transaction items
        const itemsContainer = document.getElementById('transaction-items');
        itemsContainer.innerHTML = '';
        
        items.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.nama}</td>
                <td>${item.jumlah} ${item.satuan || ''}</td>
                <td>Rp ${formatRupiah(item.harga * item.jumlah)}</td>
            `;
            itemsContainer.appendChild(row);
        });
        
        // Set total
        document.getElementById('transaction-total').textContent = `Rp ${formatRupiah(total)}`;
        
        // Show modal
        $('#transactionModal').modal('show');
    };
    
    // Handle confirm transaction button click
    document.getElementById('confirmTransactionBtn').addEventListener('click', function() {
        if (transactionForm) {
            // Show loading state
            this.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Memproses...';
            this.disabled = true;
            
            transactionForm.submit();
        }
        $('#transactionModal').modal('hide');
    });
    
    // Reset form when modal is hidden
    $('#transactionModal').on('hidden.bs.modal', function() {
        transactionForm = null;
        transactionData = null;
        document.getElementById('transaction-items').innerHTML = '';
        document.getElementById('transaction-total').textContent = 'Rp 0';
        
        // Reset confirm button
        const confirmBtn = document.getElementById('confirmTransactionBtn');
        confirmBtn.innerHTML = '<i class="mdi mdi-credit-card"></i> Ya, Proses Transaksi';
        confirmBtn.disabled = false;
    });
    
    // Helper function for formatting currency (if not already defined)
    if (typeof formatRupiah === 'undefined') {
        window.formatRupiah = function(angka) {
            return parseInt(angka).toLocaleString('id-ID');
        };
    }
});
</script>