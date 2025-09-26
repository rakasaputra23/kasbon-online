<!-- Modal Reject PPK - FIXED VERSION -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="fas fa-times-circle mr-2"></i>Tolak PPK
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Perhatian!</strong> Anda akan menolak PPK dengan nomor dokumen: <strong>{{ $ppk->no_dokumen }}</strong><br>
                        <small>PPK yang ditolak akan dikembalikan ke pembuat untuk diperbaiki.</small>
                    </div>

                    <!-- PPK Information Summary -->
                    <div class="card bg-light mb-3">
                        <div class="card-body p-3">
                            <h6 class="card-title mb-2">
                                <i class="fas fa-file-alt mr-1"></i>Informasi PPK
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small><strong>Pembuat:</strong> {{ $ppk->creator->nama ?? 'Unknown' }}</small><br>
                                    <small><strong>Divisi:</strong> {{ $ppk->divisi }}</small><br>
                                    <small><strong>Kode Anggaran:</strong> {{ $ppk->kode_anggaran ?: '-' }}</small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Total Nilai:</strong> <span class="text-danger">Rp {{ number_format($ppk->total_nilai, 0, ',', '.') }}</span></small><br>
                                    <small><strong>Periode:</strong> {{ $ppk->diajukan_tanggal->format('d/m/Y') }} - {{ $ppk->kembali_tanggal->format('d/m/Y') }}</small><br>
                                    <small><strong>Jumlah Aktivitas:</strong> {{ $ppk->details->count() }} item</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reject_catatan" class="required">
                            <i class="fas fa-comment mr-1"></i>Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="reject_catatan" name="catatan" rows="5" 
                                  placeholder="Masukkan alasan yang jelas mengapa PPK ini ditolak..." required></textarea>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Wajib diisi!</strong> Berikan alasan yang jelas dan konstruktif agar pembuat PPK dapat memperbaiki. 
                            Minimal 10 karakter.
                        </small>
                        <div class="mt-1">
                            <small class="text-muted">Karakter: <span id="charCount">0</span>/500</small>
                        </div>
                    </div>

                    <!-- Common Rejection Reasons (Quick Fill) -->
                    <div class="form-group">
                        <label class="text-muted">
                            <i class="fas fa-lightbulb mr-1"></i>Alasan Umum (Klik untuk mengisi otomatis)
                        </label>
                        <div class="btn-group-vertical btn-group-sm w-100" role="group">
                            <button type="button" class="btn btn-outline-secondary text-left quick-reason-btn" 
                                    data-reason="Dokumen lampiran tidak lengkap atau tidak sesuai dengan ketentuan yang berlaku.">
                                <i class="fas fa-paperclip mr-1"></i>Dokumen tidak lengkap
                            </button>
                            <button type="button" class="btn btn-outline-secondary text-left quick-reason-btn" 
                                    data-reason="Rencana anggaran tidak realistis atau terlalu tinggi dibanding aktivitas yang direncanakan.">
                                <i class="fas fa-calculator mr-1"></i>Anggaran tidak realistis
                            </button>
                            <button type="button" class="btn btn-outline-secondary text-left quick-reason-btn" 
                                    data-reason="Aktivitas yang direncanakan tidak sesuai dengan tujuan dan kebutuhan organisasi.">
                                <i class="fas fa-tasks mr-1"></i>Aktivitas tidak sesuai
                            </button>
                            <button type="button" class="btn btn-outline-secondary text-left quick-reason-btn" 
                                    data-reason="Jadwal pelaksanaan tidak memungkinkan atau bertabrakan dengan kegiatan lain yang sudah terjadwal.">
                                <i class="fas fa-calendar-times mr-1"></i>Jadwal tidak memungkinkan
                            </button>
                            <button type="button" class="btn btn-outline-secondary text-left quick-reason-btn" 
                                    data-reason="Informasi yang disediakan tidak lengkap atau kurang jelas untuk evaluasi yang tepat.">
                                <i class="fas fa-question-circle mr-1"></i>Informasi kurang lengkap
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Catatan:</strong> Setelah PPK ditolak, pembuat akan menerima notifikasi dan dapat memperbaiki 
                        lalu mengajukan kembali PPK tersebut.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-arrow-left mr-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-danger" id="rejectSubmitBtn" disabled>
                        <i class="fas fa-times mr-1"></i>Tolak PPK
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
                           type === 'error' ? 'error' : 
                           type === 'warning' ? 'warning' : 'info';
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
        
        // Method 3: From form serialize (fallback)
        const formToken = $('#rejectForm').find('input[name="_token"]').val();
        if (formToken) return formToken;
        
        console.error('CSRF token not found!');
        return null;
    }

    // Character counter and validation
    $('#reject_catatan').on('input', function() {
        const text = $(this).val();
        const length = text.length;
        const submitBtn = $('#rejectSubmitBtn');
        
        $('#charCount').text(length);
        
        // Enable/disable submit button based on minimum character requirement
        if (length >= 10 && length <= 500) {
            submitBtn.prop('disabled', false);
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            submitBtn.prop('disabled', true);
            if (length > 0) {
                $(this).removeClass('is-valid').addClass('is-invalid');
            } else {
                $(this).removeClass('is-valid is-invalid');
            }
        }
        
        // Character limit warnings
        if (length > 450 && length < 500) {
            $('#charCount').removeClass('text-danger').addClass('text-warning');
        } else if (length >= 500) {
            $(this).val(text.substring(0, 500));
            $('#charCount').text('500').removeClass('text-warning').addClass('text-danger');
            showAlert('warning', 'Batas Karakter', 'Maksimal 500 karakter tercapai');
        } else {
            $('#charCount').removeClass('text-warning text-danger');
        }
    });
    
    // Quick reason buttons
    $('.quick-reason-btn').on('click', function() {
        const reason = $(this).data('reason');
        const currentText = $('#reject_catatan').val().trim();
        
        if (currentText === '') {
            $('#reject_catatan').val(reason);
        } else {
            $('#reject_catatan').val(currentText + '\n\n' + reason);
        }
        
        // Trigger input event to update character counter
        $('#reject_catatan').trigger('input');
        
        // Focus on textarea for further editing
        $('#reject_catatan').focus();
        
        // Scroll to end of text
        const textarea = $('#reject_catatan')[0];
        textarea.scrollTop = textarea.scrollHeight;
    });
    
    // Enhanced confirmation dialog
    function showConfirmationDialog(callback) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Penolakan',
                text: 'Yakin ingin menolak PPK ini? Tindakan ini tidak dapat dibatalkan dan PPK akan dikembalikan ke pembuat.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-times mr-1"></i>Ya, Tolak PPK',
                cancelButtonText: '<i class="fas fa-arrow-left mr-1"></i>Batal',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        } else {
            if (confirm('Yakin ingin menolak PPK ini? Tindakan ini tidak dapat dibatalkan dan PPK akan dikembalikan ke pembuat.')) {
                callback();
            }
        }
    }
    
    // Process reject function
    function processReject(submitBtn, originalText, catatan) {
        const csrfToken = getCSRFToken();
        if (!csrfToken) {
            showAlert('error', 'Error', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
            return;
        }

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...');
        
        $.ajax({
            url: "{{ route('ppk.reject', $ppk->id) }}",
            method: 'POST',
            data: {
                catatan: catatan,
                _token: csrfToken
            },
            timeout: 30000,
            success: function(response) {
                console.log('Reject response:', response);
                
                if (response && response.success) {
                    $('#rejectModal').modal('hide');
                    showAlert('success', 'Berhasil', response.message || 'PPK berhasil ditolak');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    const errorMsg = response?.message || 'Response tidak valid dari server';
                    showAlert('error', 'Gagal', errorMsg);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('Reject error:', {xhr, status, error});
                
                let message = 'Terjadi kesalahan saat menolak PPK';
                
                if (xhr.status === 403) {
                    message = 'Anda tidak memiliki akses untuk menolak PPK ini';
                } else if (xhr.status === 404) {
                    message = 'PPK tidak ditemukan atau URL tidak valid';
                } else if (xhr.status === 422) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        message = Object.values(errors).flat().join('\n');
                    } else {
                        message = xhr.responseJSON?.message || 'Alasan penolakan tidak valid';
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
    }
    
    // Handle form submission
    $('#rejectForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#rejectSubmitBtn');
        const originalText = submitBtn.html();
        const catatan = $('#reject_catatan').val().trim();
        
        // Final validation
        if (catatan.length < 10) {
            showAlert('error', 'Validasi Gagal', 'Alasan penolakan harus minimal 10 karakter.');
            $('#reject_catatan').focus();
            return;
        }

        if (catatan.length > 500) {
            showAlert('error', 'Validasi Gagal', 'Alasan penolakan maksimal 500 karakter.');
            $('#reject_catatan').focus();
            return;
        }
        
        // Show confirmation dialog
        showConfirmationDialog(() => {
            processReject(submitBtn, originalText, catatan);
        });
    });

    // Reset form when modal is closed
    $('#rejectModal').on('hidden.bs.modal', function() {
        $('#rejectForm')[0].reset();
        $('#charCount').text('0').removeClass('text-warning text-danger');
        $('#reject_catatan').removeClass('is-valid is-invalid');
        $('#rejectSubmitBtn').prop('disabled', true).html('<i class="fas fa-times mr-1"></i>Tolak PPK');
    });
    
    // Show modal with focus on textarea
    $('#rejectModal').on('shown.bs.modal', function() {
        $('#reject_catatan').focus();
    });

    // Prevent accidental modal close when user is typing
    $('#rejectModal').on('hide.bs.modal', function(e) {
        const catatan = $('#reject_catatan').val().trim();
        if (catatan.length > 10 && !$(e.target).hasClass('modal') && !$(e.relatedTarget).hasClass('btn-secondary')) {
            if (!confirm('Anda memiliki alasan penolakan yang sudah diketik. Yakin ingin menutup modal?')) {
                e.preventDefault();
            }
        }
    });
});
</script>