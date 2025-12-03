{{-- Reusable Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark border-danger">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-white" id="deleteModalLabel">
                    <i class="mdi mdi-alert-circle text-danger"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-white">
                <div class="text-center mb-3">
                    <i class="mdi mdi-delete-forever text-danger" style="font-size: 48px;"></i>
                </div>
                <p class="text-center mb-3" id="deleteMessage">
                    Apakah Anda yakin ingin menghapus data ini?
                </p>
                <div class="alert alert-warning">
                    <i class="mdi mdi-alert"></i> 
                    <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                </div>
            </div>
            <div class="modal-footer border-danger">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="mdi mdi-close"></i> Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="mdi mdi-delete"></i> Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Delete Modal --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let deleteForm = null;
    
    // Function to show delete modal
    window.showDeleteModal = function(form, message = null) {
        deleteForm = form;
        
        // Set custom message if provided
        const messageElement = document.getElementById('deleteMessage');
        if (messageElement) {
            if (message) {
                messageElement.textContent = message;
            } else {
                messageElement.textContent = 'Apakah Anda yakin ingin menghapus data ini?';
            }
        }
        
        // Show modal
        if (typeof $ !== 'undefined' && $('#deleteModal').length) {
            $('#deleteModal').modal('show');
        } else {
            console.error('Bootstrap modal or jQuery not available');
        }
    };
    
    // Handle confirm delete button click
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (deleteForm) {
                // Show loading state
                this.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Menghapus...';
                this.disabled = true;
                
                deleteForm.submit();
            }
            if (typeof $ !== 'undefined') {
                $('#deleteModal').modal('hide');
            }
        });
    }
    
    // Reset form when modal is hidden
    if (typeof $ !== 'undefined') {
        $('#deleteModal').on('hidden.bs.modal', function() {
            deleteForm = null;
            const messageElement = document.getElementById('deleteMessage');
            if (messageElement) {
                messageElement.textContent = 'Apakah Anda yakin ingin menghapus data ini?';
            }
            
            // Reset confirm button
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            if (confirmBtn) {
                confirmBtn.innerHTML = '<i class="mdi mdi-delete"></i> Ya, Hapus';
                confirmBtn.disabled = false;
            }
        });
    }
});
</script>