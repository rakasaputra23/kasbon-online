<!-- Modal Approval with Disposisi - FIXED VERSION -->
<div class="modal fade" id="approvalDisposisiModal" tabindex="-1" role="dialog" aria-labelledby="approvalDisposisiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="approvalDisposisiModalLabel">
                    <i class="fas fa-check-circle mr-2"></i>Approve PPK & Disposisi Anggaran
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approvalDisposisiForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Anda akan menyetujui PPK dengan nomor dokumen: <strong>{{ $ppk->no_dokumen }}</strong><br>
                        <small>Sebagai Kepala Departemen, Anda perlu mengisi informasi disposisi anggaran.</small>
                    </div>

                    <!-- PPK Information Summary -->
                    <div class="card bg-light mb-3">
                        <div class="card-body p-3">
                            <h6 class="card-title mb-2">
                                <i class="fas fa-file-alt mr-1"></i>Ringkasan PPK
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small><strong>Divisi:</strong> {{ $ppk->divisi }}</small><br>
                                    <small><strong>Kode Anggaran:</strong> {{ $ppk->kode_anggaran ?: '-' }}</small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Total Nilai:</strong> <span class="text-primary">Rp {{ number_format($ppk->total_nilai, 0, ',', '.') }}</span></small><br>
                                    <small><strong>Periode:</strong> {{ $ppk->diajukan_tanggal->format('d/m/Y') }} - {{ $ppk->kembali_tanggal->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Disposisi Anggaran Form -->
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-calculator mr-1"></i>Disposisi Anggaran
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="disposisi_dana_tersedia" class="required">
                                            <i class="fas fa-wallet mr-1"></i>Dana Tersedia
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" class="form-control" id="disposisi_dana_tersedia" 
                                                   name="disposisi_dana_tersedia" min="0" step="1000" required
                                                   placeholder="0">
                                        </div>
                                        <small class="form-text text-muted">Total dana yang tersedia dalam anggaran</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="disposisi_dana_terpakai" class="required">
                                            <i class="fas fa-minus-circle mr-1"></i>Dana Terpakai
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" class="form-control" id="disposisi_dana_terpakai" 
                                                   name="disposisi_dana_terpakai" min="0" step="1000" required
                                                   placeholder="0">
                                        </div>
                                        <small class="form-text text-muted">Dana yang sudah digunakan sebelumnya</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="disposisi_sisa_anggaran" class="required">
                                            <i class="fas fa-piggy-bank mr-1"></i>Sisa Anggaran
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" class="form-control" id="disposisi_sisa_anggaran" 
                                                   name="disposisi_sisa_anggaran" min="0" step="1000" required
                                                   placeholder="0" readonly>
                                        </div>
                                        <small class="form-text text-muted">Otomatis dihitung: Dana Tersedia - Dana Terpakai</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-muted">
                                            <i class="fas fa-hand-holding-usd mr-1"></i>Nilai PPK Ini
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">Rp</span>
                                            </div>
                                            <input type="text" class="form-control bg-light" 
                                                   value="{{ number_format($ppk->total_nilai, 0, ',', '.') }}" readonly>
                                        </div>
                                        <small class="form-text text-muted">Total nilai PPK yang akan disetujui</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Validation Alert -->
                            <div class="alert alert-warning" id="budgetValidationAlert" style="display: none;">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span id="budgetValidationMessage"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Approval -->
                    <div class="form-group">
                        <label for="disposisi_catatan">
                            <i class="fas fa-comment mr-1"></i>Catatan Approval <small class="text-muted">(Opsional)</small>
                        </label>
                        <textarea class="form-control" id="disposisi_catatan" name="catatan" rows="3" 
                                  placeholder="Masukkan catatan approval dan disposisi anggaran..."></textarea>
                        <small class="form-text text-muted">
                            Catatan ini akan terlihat dalam riwayat approval
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="approveDisposisiSubmitBtn">
                        <i class="fas fa-check mr-1"></i>Approve & Disposisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const ppkNilai = {{ $ppk->total_nilai }};
    
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
        
        // Method 3: From form serialize (fallback)
        const formToken = $('#approvalDisposisiForm').find('input[name="_token"]').val();
        if (formToken) return formToken;
        
        console.error('CSRF token not found!');
        return null;
    }

    // Helper function for number formatting
    function numberFormat(number, decimals = 0, decPoint = '.', thousandsSep = ',') {
        number = parseFloat(number) || 0;
        const roundedNumber = Math.round(Math.abs(number) * Math.pow(10, decimals)) / Math.pow(10, decimals);
        const numberString = roundedNumber.toString();
        
        const numberArray = numberString.split('.');
        let integerPart = numberArray[0];
        const decimalPart = numberArray.length > 1 ? decPoint + numberArray[1] : '';
        
        const rgx = /(\d+)(\d{3})/;
        while (rgx.test(integerPart)) {
            integerPart = integerPart.replace(rgx, '$1' + thousandsSep + '$2');
        }
        
        return (number < 0 ? '-' : '') + integerPart + decimalPart;
    }
    
    // Auto calculate sisa anggaran
    function calculateSisaAnggaran() {
        const danaTersedia = parseFloat($('#disposisi_dana_tersedia').val()) || 0;
        const danaTerpakai = parseFloat($('#disposisi_dana_terpakai').val()) || 0;
        const sisaAnggaran = danaTersedia - danaTerpakai;
        
        $('#disposisi_sisa_anggaran').val(sisaAnggaran);
        
        // Validate budget
        validateBudget(sisaAnggaran);
    }
    
    // Validate budget sufficiency
    function validateBudget(sisaAnggaran) {
        const alertDiv = $('#budgetValidationAlert');
        const messageSpan = $('#budgetValidationMessage');
        const submitBtn = $('#approveDisposisiSubmitBtn');
        
        if (sisaAnggaran < ppkNilai) {
            const kekurangan = ppkNilai - sisaAnggaran;
            messageSpan.html(`Sisa anggaran tidak mencukupi! Kekurangan: <strong>Rp ${numberFormat(kekurangan, 0, ',', '.')}</strong>`);
            alertDiv.removeClass('alert-warning alert-success').addClass('alert-danger').show();
            submitBtn.prop('disabled', true);
        } else if (sisaAnggaran === ppkNilai) {
            messageSpan.html('Sisa anggaran pas dengan nilai PPK.');
            alertDiv.removeClass('alert-danger alert-warning').addClass('alert-success').show();
            submitBtn.prop('disabled', false);
        } else {
            const sisa = sisaAnggaran - ppkNilai;
            messageSpan.html(`Sisa anggaran mencukupi. Sisa setelah PPK: <strong>Rp ${numberFormat(sisa, 0, ',', '.')}</strong>`);
            alertDiv.removeClass('alert-danger alert-warning').addClass('alert-success').show();
            submitBtn.prop('disabled', false);
        }
    }
    
    // Event listeners for calculation
    $('#disposisi_dana_tersedia, #disposisi_dana_terpakai').on('input', calculateSisaAnggaran);
    
    // Validate dana terpakai tidak melebihi dana tersedia
    $('#disposisi_dana_terpakai').on('input', function() {
        const danaTersedia = parseFloat($('#disposisi_dana_tersedia').val()) || 0;
        const danaTerpakai = parseFloat($(this).val()) || 0;
        
        if (danaTerpakai > danaTersedia) {
            $(this).val(danaTersedia);
            calculateSisaAnggaran();
            showAlert('warning', 'Peringatan', 'Dana terpakai tidak boleh melebihi dana tersedia');
        }
    });
    
    // Handle form submission
    $('#approvalDisposisiForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#approveDisposisiSubmitBtn');
        const originalText = submitBtn.html();
        
        // Final validation
        const sisaAnggaran = parseFloat($('#disposisi_sisa_anggaran').val()) || 0;
        if (sisaAnggaran < ppkNilai) {
            showAlert('error', 'Validasi Gagal', 'Sisa anggaran tidak mencukupi untuk PPK ini.');
            return;
        }

        const danaTersedia = parseFloat($('#disposisi_dana_tersedia').val()) || 0;
        const danaTerpakai = parseFloat($('#disposisi_dana_terpakai').val()) || 0;

        if (danaTersedia <= 0) {
            showAlert('error', 'Validasi Gagal', 'Dana tersedia harus lebih dari 0.');
            $('#disposisi_dana_tersedia').focus();
            return;
        }

        if (danaTerpakai < 0) {
            showAlert('error', 'Validasi Gagal', 'Dana terpakai tidak boleh negatif.');
            $('#disposisi_dana_terpakai').focus();
            return;
        }

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
                catatan: $('#disposisi_catatan').val().trim(),
                disposisi_dana_tersedia: danaTersedia,
                disposisi_dana_terpakai: danaTerpakai,
                disposisi_sisa_anggaran: sisaAnggaran,
                _token: csrfToken
            },
            timeout: 30000,
            success: function(response) {
                console.log('Approval Disposisi response:', response);
                
                if (response && response.success) {
                    $('#approvalDisposisiModal').modal('hide');
                    showAlert('success', 'Berhasil', response.message || 'PPK berhasil disetujui dengan disposisi anggaran');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    const errorMsg = response?.message || 'Response tidak valid dari server';
                    showAlert('error', 'Gagal', errorMsg);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('Approval Disposisi error:', {xhr, status, error});
                
                let message = 'Terjadi kesalahan saat approve PPK';
                
                if (xhr.status === 403) {
                    message = 'Anda tidak memiliki akses untuk approve PPK ini';
                } else if (xhr.status === 404) {
                    message = 'PPK tidak ditemukan atau URL tidak valid';
                } else if (xhr.status === 422) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        message = Object.values(errors).flat().join('\n');
                    } else {
                        message = xhr.responseJSON?.message || 'Data disposisi tidak valid';
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
    $('#approvalDisposisiModal').on('hidden.bs.modal', function() {
        $('#approvalDisposisiForm')[0].reset();
        $('#budgetValidationAlert').hide();
        $('#approveDisposisiSubmitBtn').prop('disabled', false).html('<i class="fas fa-check mr-1"></i>Approve & Disposisi');
    });

    // Focus on first input when modal opens
    $('#approvalDisposisiModal').on('shown.bs.modal', function() {
        $('#disposisi_dana_tersedia').focus();
    });
});
</script>