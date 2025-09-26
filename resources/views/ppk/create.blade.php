@extends('layouts.app')

@section('title', 'Buat PPK Baru - Kasbon Online System')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Buat PPK Baru</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ppk.index') }}">PPK</a></li>
            <li class="breadcrumb-item active">Buat Baru</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<form id="ppk-form" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- Main Form Section -->
        <div class="col-md-8">
            <!-- Preview Nomor Dokumen -->
            <div class="info-box bg-light mb-3">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-hashtag"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Nomor Dokumen</span>
                    <span class="info-box-number" id="no-dokumen-display">Loading...</span>
                    <div class="info-box-more text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Nomor dokumen akan di-generate otomatis saat data disimpan
                    </div>
                </div>
                <div class="info-box-icon bg-light ml-auto">
                    <button type="button" id="refresh-number" class="btn btn-sm btn-outline-primary" title="Refresh Preview">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Form Informasi PPK -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>Informasi PPK
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="divisi">
                                    Divisi <span class="text-danger">*</span>
                                </label>
                                <select id="divisi" name="divisi" class="form-control select2" required>
                                    <option value="">Pilih Divisi</option>
                                </select>
                                <div class="invalid-feedback">
                                    Divisi wajib dipilih.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_unit">Kode Unit</label>
                                <input type="text" id="kode_unit" name="kode_unit" class="form-control" 
                                       placeholder="Masukkan kode unit" maxlength="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_anggaran">Kode Anggaran</label>
                                <input type="text" id="kode_anggaran" name="kode_anggaran" class="form-control" 
                                       placeholder="Masukkan kode anggaran" maxlength="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jangka_waktu">Jangka Waktu</label>
                                <input type="text" id="jangka_waktu" name="jangka_waktu" class="form-control" 
                                       placeholder="Contoh: 30 hari" maxlength="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="diajukan_tanggal">
                                    Tanggal Pengajuan <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="diajukan_tanggal" name="diajukan_tanggal" 
                                       class="form-control" required>
                                <div class="invalid-feedback">
                                    Tanggal pengajuan wajib diisi.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kembali_tanggal">
                                    Tanggal Kembali <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="kembali_tanggal" name="kembali_tanggal" 
                                       class="form-control" required>
                                <div class="invalid-feedback">
                                    Tanggal kembali wajib diisi dan tidak boleh lebih awal dari tanggal pengajuan.
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="lampiran">
                                    <i class="fas fa-paperclip mr-1"></i>Lampiran Dokumen
                                </label>
                                <div class="custom-file">
                                    <input type="file" id="lampiran" name="lampiran[]" 
                                           class="custom-file-input" multiple 
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <label class="custom-file-label" for="lampiran">Pilih file...</label>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Format: PDF, JPG, PNG, DOC, DOCX. Maksimal 5MB per file.
                                </small>
                                <div id="file-preview" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Aktivitas -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-list mr-2"></i>Daftar Aktivitas
                    </h3>
                    <button type="button" id="add-aktivitas" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Aktivitas
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" id="aktivitas-table">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="20%">Tanggal</th>
                                    <th width="45%">Aktivitas</th>
                                    <th width="20%">Rencana Biaya (Rp)</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="aktivitas-table-body">
                                <!-- Dynamic rows will be added here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 bg-light border-top">
                        <div class="row">
                            <div class="col-sm-8">
                                <span class="text-muted">
                                    <i class="fas fa-list-ol mr-1"></i>
                                    Total aktivitas: <span id="total-aktivitas" class="font-weight-bold">0</span>
                                </span>
                            </div>
                            <div class="col-sm-4 text-right">
                                <strong class="text-primary">
                                    <i class="fas fa-calculator mr-1"></i>
                                    Total Nilai: Rp <span id="total-nilai">0</span>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 1rem;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>Informasi
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Status Info -->
                    <div class="info-box bg-light mb-3">
                        <span class="info-box-icon bg-secondary">
                            <i class="fas fa-file-alt"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Status</span>
                            <span class="info-box-number">
                                <span class="badge badge-secondary">Draft</span>
                            </span>
                        </div>
                    </div>

                    <!-- Progress Info -->
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Progress Pengisian Form:</small>
                        <div class="progress progress-sm">
                            <div class="progress-bar" role="progressbar" id="form-progress" 
                                 style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                <span class="sr-only">0% Complete</span>
                            </div>
                        </div>
                        <small class="text-muted"><span id="progress-text">0% selesai</span></small>
                    </div>

                    <!-- Guidelines -->
                    <div class="info-box bg-light mb-3">
                        <span class="info-box-icon bg-info">
                            <i class="fas fa-info"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Panduan</span>
                            <ul class="info-box-more mb-0 pl-3">
                                <li>PPK yang sudah disubmit tidak dapat diedit</li>
                                <li>Pastikan semua data sudah benar sebelum submit</li>
                                <li>Minimal harus ada 1 aktivitas</li>
                                <li>Total nilai akan dihitung otomatis</li>
                                <li>File lampiran bersifat opsional</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-group mb-0 mt-3">
                        <button type="submit" class="btn btn-primary btn-block mb-2" id="submit-btn">
                            <i class="fas fa-save mr-2"></i> Simpan Draft
                        </button>
                        <button type="button" class="btn btn-secondary btn-block" onclick="window.history.back()">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Template Row Aktivitas -->
<template id="aktivitas-row-template">
    <tr class="aktivitas-row" data-index="INDEX">
        <td class="text-center aktivitas-no align-middle">1</td>
        <td>
            <input type="date" name="aktivitas[INDEX][tanggal_aktivitas]" 
                   class="form-control aktivitas-tanggal" required>
        </td>
        <td>
            <textarea name="aktivitas[INDEX][aktivitas]" 
                      class="form-control aktivitas-desc" rows="2" 
                      placeholder="Uraian aktivitas..." required></textarea>
        </td>
        <td>
            <input type="number" name="aktivitas[INDEX][rencana]" 
                   class="form-control aktivitas-biaya" min="0" step="1" 
                   placeholder="0" required>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-danger btn-sm remove-aktivitas" 
                    title="Hapus aktivitas">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>
@endsection

@push('styles')
<style>
/* Custom styles untuk form PPK */
.sticky-top {
    z-index: 1020;
}

.form-control:focus {
    border-color: var(--kai-primary-light);
    box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
}

.select2-container--bootstrap4 .select2-selection--single:focus {
    border-color: var(--kai-primary-light);
    box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
}

.custom-file-input:focus ~ .custom-file-label {
    border-color: var(--kai-primary-light);
    box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
}

#aktivitas-table tbody tr:hover {
    background-color: var(--kai-gray-50);
}

.file-preview-item {
    display: inline-block;
    margin: 2px;
    padding: 4px 8px;
    background: var(--kai-gray-100);
    border-radius: 4px;
    font-size: 12px;
    border: 1px solid var(--kai-gray-300);
}

.file-preview-item .remove-file {
    margin-left: 8px;
    color: var(--kai-danger);
    cursor: pointer;
    font-weight: bold;
}

.file-preview-item .remove-file:hover {
    color: #dc3545;
}

.progress-bar {
    transition: width 0.3s ease;
}

.aktivitas-row {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Custom info-box styling */
.info-box-more {
    font-size: 0.875rem;
    margin-top: 4px;
}

.info-box.bg-info {
    background-color: #d1ecf1 !important;
    border: 1px solid #bee5eb;
}

.info-box.bg-info .info-box-icon {
    color: #0c5460;
}

/* Responsive improvements - PERBAIKAN UNTUK MOBILE */
@media (max-width: 768px) {
    .sticky-top {
        position: relative !important;
    }
    
    /* Perbaikan untuk table aktivitas mobile */
    #aktivitas-table {
        min-width: 700px; /* Tetapkan minimum width untuk horizontal scroll */
        font-size: 0.875rem;
    }
    
    /* Kolom-kolom tetap dengan width yang cukup */
    #aktivitas-table th:nth-child(1) { min-width: 40px; }  /* No */
    #aktivitas-table th:nth-child(2) { min-width: 140px; } /* Tanggal */
    #aktivitas-table th:nth-child(3) { min-width: 250px; } /* Aktivitas */
    #aktivitas-table th:nth-child(4) { min-width: 180px; } /* Rencana Biaya */
    #aktivitas-table th:nth-child(5) { min-width: 60px; }  /* Aksi */
    
    #aktivitas-table td:nth-child(1) { min-width: 40px; }
    #aktivitas-table td:nth-child(2) { min-width: 140px; }
    #aktivitas-table td:nth-child(3) { min-width: 250px; }
    #aktivitas-table td:nth-child(4) { min-width: 180px; }
    #aktivitas-table td:nth-child(5) { min-width: 60px; }
    
    /* Input fields dalam tabel dengan width penuh */
    .aktivitas-tanggal {
        min-width: 130px !important;
        width: 100% !important;
    }
    
    .aktivitas-desc {
        min-width: 240px !important;
        width: 100% !important;
        min-height: 60px !important;
        resize: vertical;
    }
    
    .aktivitas-biaya {
        min-width: 170px !important;
        width: 100% !important;
    }
    
    /* Pastikan table container bisa di-scroll horizontal */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Button aksi tetap compact */
    .remove-aktivitas {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.75rem !important;
    }
}

/* Untuk tablet */
@media (min-width: 769px) and (max-width: 1024px) {
    #aktivitas-table {
        font-size: 0.9rem;
    }
    
    .aktivitas-desc {
        min-height: 50px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let aktivitasIndex = 0;
    let isFormSubmitting = false;

    // Initialize form
    initializeForm();
    
    // Auto generate nomor dokumen saat halaman load
    generateNomorDokumen();

    // Load divisi data
    loadDivisiData();

    // Add first aktivitas row
    addAktivitasRow();

    // Set default dates
    setDefaultDates();

    // Event Handlers
    setupEventHandlers();

    // ===== INITIALIZATION FUNCTIONS =====
    function initializeForm() {
        // Initialize select2
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih...',
            allowClear: true
        });

        // Initialize custom file input
        $('.custom-file-input').on('change', function() {
            handleFileSelection(this);
        });

        // Update progress on load
        updateFormProgress();
    }

    function setupEventHandlers() {
        // Date validation
        $('#diajukan_tanggal').on('change', function() {
            const diajukanDate = $(this).val();
            $('#kembali_tanggal').attr('min', diajukanDate);
            validateDates();
            updateFormProgress();
        });

        $('#kembali_tanggal').on('change', function() {
            validateDates();
            updateFormProgress();
        });

        // Divisi selection
        $('#divisi').on('change', function() {
            updateFormProgress();
        });

        // Add aktivitas row
        $('#add-aktivitas').on('click', function() {
            addAktivitasRow();
        });

        // Remove aktivitas row
        $(document).on('click', '.remove-aktivitas', function() {
            removeAktivitasRow($(this));
        });

        // Calculate total when biaya changes
        $(document).on('input', '.aktivitas-biaya', function() {
            calculateTotal();
            updateFormProgress();
        });

        // Update progress when aktivitas fields change
        $(document).on('input change', '.aktivitas-tanggal, .aktivitas-desc', function() {
            updateFormProgress();
        });

        // Refresh nomor dokumen
        $('#refresh-number').on('click', function() {
            const btn = $(this);
            const originalHtml = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
            
            generateNomorDokumen().finally(function() {
                btn.html(originalHtml).prop('disabled', false);
            });
        });

        // Form submission
        $('#ppk-form').on('submit', function(e) {
            e.preventDefault();
            handleFormSubmission();
        });

        // Prevent form resubmission
        window.addEventListener('beforeunload', function(e) {
            if (isFormSubmitting) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    }

    // ===== AUTO GENERATE NOMOR DOKUMEN =====
    function generateNomorDokumen() {
        $('#no-dokumen-display').text('Loading...');
        
        return $.ajax({
            url: "{{ route('ppk.getNextNumber') }}",
            method: 'GET',
            timeout: 10000
        })
        .done(function(response) {
            if (response.success) {
                $('#no-dokumen-display').text(response.no_dokumen);
                updateHiddenNomorDokumen(response.no_dokumen);
            } else {
                generateClientSideNumber();
            }
        })
        .fail(function(xhr, status, error) {
            console.warn('Failed to fetch document number from server:', error);
            generateClientSideNumber();
        });
    }

    function generateClientSideNumber() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const date = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        
        const nomorDokumen = `PPK-${year}-${month}${date}${hours}${minutes}`;
        $('#no-dokumen-display').text(nomorDokumen);
        updateHiddenNomorDokumen(nomorDokumen);
    }

    function updateHiddenNomorDokumen(nomorDokumen) {
        if ($('#no_dokumen_hidden').length === 0) {
            $('#ppk-form').append(`<input type="hidden" id="no_dokumen_hidden" name="no_dokumen_preview" value="${nomorDokumen}">`);
        } else {
            $('#no_dokumen_hidden').val(nomorDokumen);
        }
    }

    // ===== DATA LOADING =====
    function loadDivisiData() {
        $.ajax({
            url: "{{ route('ppk.divisi-options') }}",
            method: 'GET',
            timeout: 10000
        })
        .done(function(data) {
            const select = $('#divisi');
            select.empty().append('<option value="">Pilih Divisi</option>');
            
            if (Array.isArray(data)) {
                data.forEach(function(divisi) {
                    select.append(`<option value="${divisi}">${divisi}</option>`);
                });
            }
        })
        .fail(function(xhr, status, error) {
            console.error('Error loading divisi data:', error);
            showAlert('error', 'Error', 'Gagal memuat data divisi. Silakan refresh halaman.');
        });
    }

    // ===== AKTIVITAS MANAGEMENT =====
    function addAktivitasRow() {
        const template = $('#aktivitas-row-template').html();
        const newRow = template.replace(/INDEX/g, aktivitasIndex);
        $('#aktivitas-table-body').append(newRow);
        aktivitasIndex++;
        updateAktivitasNumbers();
        calculateTotal();
        updateFormProgress();
    }

    function removeAktivitasRow(button) {
        const rows = $('.aktivitas-row').length;
        if (rows > 1) {
            button.closest('tr').fadeOut(300, function() {
                $(this).remove();
                updateAktivitasNumbers();
                calculateTotal();
                updateFormProgress();
            });
        } else {
            showAlert('warning', 'Peringatan', 'Minimal harus ada 1 aktivitas');
        }
    }

    function updateAktivitasNumbers() {
        $('.aktivitas-row').each(function(index) {
            $(this).find('.aktivitas-no').text(index + 1);
        });
        $('#total-aktivitas').text($('.aktivitas-row').length);
    }

    function calculateTotal() {
        let total = 0;
        $('.aktivitas-biaya').each(function() {
            const value = parseFloat($(this).val()) || 0;
            total += value;
        });
        $('#total-nilai').text(formatRupiah(total));
    }

    // ===== VALIDATION =====
    function validateDates() {
        const diajukanDate = $('#diajukan_tanggal').val();
        const kembaliDate = $('#kembali_tanggal').val();
        
        if (diajukanDate && kembaliDate && kembaliDate < diajukanDate) {
            $('#kembali_tanggal').addClass('is-invalid');
            showAlert('warning', 'Peringatan', 'Tanggal kembali tidak boleh lebih awal dari tanggal pengajuan');
            $('#kembali_tanggal').val('');
            return false;
        } else {
            $('#kembali_tanggal').removeClass('is-invalid');
            return true;
        }
    }

    function validateForm() {
        let isValid = true;
        const errors = [];

        // Reset validation states
        $('.form-control').removeClass('is-invalid');

        // Check required fields
        const requiredFields = [
            { field: '#divisi', message: 'Divisi wajib dipilih' },
            { field: '#diajukan_tanggal', message: 'Tanggal pengajuan wajib diisi' },
            { field: '#kembali_tanggal', message: 'Tanggal kembali wajib diisi' }
        ];

        requiredFields.forEach(function(item) {
            if (!$(item.field).val()) {
                $(item.field).addClass('is-invalid');
                errors.push(item.message);
                isValid = false;
            }
        });

        // Check aktivitas
        if ($('.aktivitas-row').length === 0) {
            errors.push('Minimal harus ada 1 aktivitas');
            isValid = false;
        } else {
            $('.aktivitas-row').each(function(index) {
                const row = $(this);
                const tanggal = row.find('.aktivitas-tanggal').val();
                const aktivitas = row.find('.aktivitas-desc').val().trim();
                const biaya = row.find('.aktivitas-biaya').val();

                if (!tanggal || !aktivitas || !biaya) {
                    row.find('.aktivitas-tanggal, .aktivitas-desc, .aktivitas-biaya').each(function() {
                        if (!$(this).val() || ($(this).hasClass('aktivitas-desc') && !$(this).val().trim())) {
                            $(this).addClass('is-invalid');
                        }
                    });
                    errors.push(`Aktivitas ${index + 1}: Semua field wajib diisi`);
                    isValid = false;
                }
            });
        }

        // Validate dates
        if (!validateDates()) {
            isValid = false;
        }

        if (!isValid) {
            showAlert('error', 'Validasi Error', errors.join('<br>'));
            // Focus on first invalid field
            $('.is-invalid').first().focus();
        }

        return isValid;
    }

    // ===== FILE HANDLING =====
    function handleFileSelection(input) {
        const files = input.files;
        let fileNames = [];
        let preview = '';

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);

            if (file.size > 5 * 1024 * 1024) { // 5MB limit
                showAlert('warning', 'Peringatan', `File ${fileName} melebihi batas 5MB`);
                continue;
            }

            fileNames.push(fileName);
            preview += `<span class="file-preview-item">
                <i class="fas fa-file mr-1"></i>${fileName} (${fileSize}MB)
                <span class="remove-file" data-index="${i}" title="Hapus file">&times;</span>
            </span>`;
        }

        $(input).next('.custom-file-label').text(
            fileNames.length > 0 ? `${fileNames.length} file dipilih` : 'Pilih file...'
        );

        $('#file-preview').html(preview);

        // Handle file removal
        $('.remove-file').on('click', function() {
            // Note: Removing individual files from FileList is complex
            // For now, clear all and let user reselect
            input.value = '';
            $(input).next('.custom-file-label').text('Pilih file...');
            $('#file-preview').empty();
        });
    }

    // ===== FORM PROGRESS =====
    function updateFormProgress() {
        let completed = 0;
        const totalSteps = 5; // divisi, tanggal diajukan, tanggal kembali, minimal 1 aktivitas, aktivitas filled

        // Check required fields
        if ($('#divisi').val()) completed++;
        if ($('#diajukan_tanggal').val()) completed++;
        if ($('#kembali_tanggal').val()) completed++;

        // Check aktivitas
        const aktivitasRows = $('.aktivitas-row');
        if (aktivitasRows.length > 0) {
            completed++;
            
            let filledRows = 0;
            aktivitasRows.each(function() {
                const row = $(this);
                const tanggal = row.find('.aktivitas-tanggal').val();
                const aktivitas = row.find('.aktivitas-desc').val().trim();
                const biaya = row.find('.aktivitas-biaya').val();
                
                if (tanggal && aktivitas && biaya) {
                    filledRows++;
                }
            });
            
            if (filledRows > 0) completed++;
        }

        const percentage = Math.round((completed / totalSteps) * 100);
        $('#form-progress').css('width', percentage + '%').attr('aria-valuenow', percentage);
        $('#progress-text').text(percentage + '% selesai');
    }

    // ===== FORM SUBMISSION =====
    function handleFormSubmission() {
        if (isFormSubmitting) return;

        if (!validateForm()) return;

        isFormSubmitting = true;
        const submitBtn = $('#submit-btn');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true)
                 .html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');

        const formData = new FormData(document.getElementById('ppk-form'));

        $.ajax({
            url: "{{ route('ppk.store') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            timeout: 30000
        })
        .done(function(response) {
            if (response.success) {
                showAlert('success', 'Berhasil!', response.message);
                
                if (response.redirect) {
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1500);
                }
            } else {
                throw new Error(response.message || 'Terjadi kesalahan saat menyimpan');
            }
        })
        .fail(function(xhr, status, error) {
            console.error('Form submission error:', error);
            
            let message = 'Terjadi kesalahan saat menyimpan';
            
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                const errorMessages = [];
                for (let field in errors) {
                    errorMessages.push(errors[field][0]);
                }
                message = errorMessages.join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (status === 'timeout') {
                message = 'Request timeout. Silakan coba lagi.';
            }
            
            showAlert('error', 'Gagal Menyimpan', message);
        })
        .always(function() {
            isFormSubmitting = false;
            submitBtn.prop('disabled', false).html(originalText);
        });
    }

    // ===== UTILITY FUNCTIONS =====
    function setDefaultDates() {
        const today = new Date().toISOString().split('T')[0];
        const nextMonth = new Date();
        nextMonth.setMonth(nextMonth.getMonth() + 1);
        const nextMonthStr = nextMonth.toISOString().split('T')[0];

        $('#diajukan_tanggal').val(today);
        $('#kembali_tanggal').val(nextMonthStr).attr('min', today);
    }

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID').format(amount);
    }

    function showAlert(type, title, text = '') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: title,
                html: text,
                timer: type === 'success' ? 3000 : 5000,
                showConfirmButton: type !== 'success',
                toast: true,
                position: 'top-end',
                customClass: {
                    popup: 'colored-toast'
                },
                background: type === 'success' ? '#d1fae5' : 
                          type === 'error' ? '#fee2e2' : 
                          type === 'warning' ? '#fef3cd' : '#dbeafe',
                color: '#1E40AF'
            });
        } else {
            // Fallback to browser alert
            alert(title + (text ? ': ' + text : ''));
        }
    }

    // ===== KEYBOARD SHORTCUTS =====
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            if (!isFormSubmitting) {
                $('#ppk-form').trigger('submit');
            }
        }
        
        // Ctrl/Cmd + Enter to add new aktivitas
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            addAktivitasRow();
        }
    });

    // ===== AUTO-SAVE DRAFT (Optional) =====
    let autoSaveTimer;
    function scheduleAutoSave() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(function() {
            // Could implement auto-save to localStorage here
            console.log('Auto-save triggered');
        }, 30000); // 30 seconds
    }

    // Schedule auto-save on form changes
    $('#ppk-form').on('input change', function() {
        scheduleAutoSave();
    });

    // ===== CLEANUP ON PAGE UNLOAD =====
    window.addEventListener('beforeunload', function() {
        clearTimeout(autoSaveTimer);
    });
});
</script>
@endpush