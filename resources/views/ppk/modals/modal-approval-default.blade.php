<!-- Modal Approval Default - FIXED VERSION -->
<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="approvalModalLabel">
                    <i class="fas fa-check-circle mr-2"></i>Approve PPK
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approvalForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Anda akan menyetujui PPK dengan nomor dokumen: <strong>{{ $ppk->no_dokumen }}</strong>
                    </div>

                    <div class="form-group">
                        <label for="approval_catatan">
                            <i class="fas fa-comment mr-1"></i>Catatan Approval <small class="text-muted">(Opsional)</small>
                        </label>
                        <textarea class="form-control" id="approval_catatan" name="catatan" rows="4" 
                                  placeholder="Masukkan catatan approval jika diperlukan..."></textarea>
                        <small class="form-text text-muted">
                            Catatan ini akan terlihat dalam riwayat approval
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="approveSubmitBtn">
                        <i class="fas fa-check mr-1"></i>Approve PPK
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Utility function for alerts - robust fallback
    function showAlert(type, title, message) {
        console.log(`[${type.toUpperCase()}] ${title}: ${message}`);
        
        if (typeof Swal !== 'undefined') {
            const iconType = type === 'success' ? 'success' : 
                           type === 'error' ? 'error' : 'info';
            Swal.fire({
                title: title,
                text: message,
                icon: iconType,
                timer: type === 'success' ? 2000 : 0,
                showConfirmButton: type !== 'success'
            });
        } else if (typeof toastr !== 'undefined') {
            toastr[type](message, title);
        } else {
            alert(`${title}: ${message}`);
        }
    }

    // Get CSRF token with multiple fallback methods
    function getCSRFToken() {
        // Method 1: Meta tag
        let token = $('meta[name="csrf-token"]').attr('content');
        if (token) return token;
        
        // Method 2: Form input
        token = $('input[name="_token"]').val();
        if (token) return token;
        
        // Method 3: From Laravel global (if available)
        if (typeof Laravel !== 'undefined' && Laravel.csrfToken) {
            return Laravel.csrfToken;
        }
        
        console.error('CSRF token not found!');
        return null;
    }

    // Handle approval form submission
    $('#approvalForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#approveSubmitBtn');
        const originalText = submitBtn.html();
        const catatan = $('#approval_catatan').val().trim();
        const csrfToken = getCSRFToken();
        
        if (!csrfToken) {
            showAlert('error', 'Error', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
            return;
        }
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Processing...');
        
        $.ajax({
            url: "{{ route('ppk.approve', $ppk->id) }}",
            method: 'POST',
            data: {
                catatan: catatan,
                _token: csrfToken
            },
            timeout: 30000, // 30 second timeout
            success: function(response) {
                console.log('Approval response:', response);
                
                if (response && response.success) {
                    $('#approvalModal').modal('hide');
                    showAlert('success', 'Berhasil', response.message || 'PPK berhasil disetujui');
                    
                    // Delayed reload to allow user to see success message
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    const errorMsg = response?.message || 'Response tidak valid dari server';
                    showAlert('error', 'Gagal', errorMsg);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('Approval error:', {xhr, status, error});
                
                let message = 'Terjadi kesalahan saat approve PPK';
                
                if (xhr.status === 403) {
                    message = 'Anda tidak memiliki akses untuk approve PPK ini';
                } else if (xhr.status === 404) {
                    message = 'PPK tidak ditemukan atau URL tidak valid';
                } else if (xhr.status === 422) {
                    // Validation errors
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        message = Object.values(errors).flat().join('\n');
                    } else {
                        message = xhr.responseJSON?.message || 'Data yang dikirim tidak valid';
                    }
                } else if (xhr.status === 419) {
                    message = 'Session expired. Silakan refresh halaman dan coba lagi';
                } else if (xhr.status >= 500) {
                    message = 'Terjadi kesalahan server. Silakan coba lagi atau hubungi administrator';
                } else if (status === 'timeout') {
                    message = 'Request timeout. Silakan coba lagi';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                showAlert('error', 'Gagal', message);
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Reset form when modal is closed
    $('#approvalModal').on('hidden.bs.modal', function() {
        $('#approvalForm')[0].reset();
        $('#approveSubmitBtn').prop('disabled', false).html('<i class="fas fa-check mr-1"></i>Approve PPK');
    });

    // Focus on textarea when modal opens
    $('#approvalModal').on('shown.bs.modal', function() {
        $('#approval_catatan').focus();
    });
});
</script>