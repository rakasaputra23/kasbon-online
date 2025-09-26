@extends('layouts.app')

@section('title', 'Edit PPK - Kasbon Online System')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Edit PPK</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ppk.index') }}">PPK</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ppk.show', $ppk->id) }}">{{ $ppk->no_dokumen }}</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<form id="ppk-form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <!-- Hidden inputs untuk data yang readonly -->
    <input type="hidden" name="no_dokumen" value="{{ $ppk->no_dokumen }}">
    <input type="hidden" name="divisi" value="{{ $ppk->divisi }}">
    <input type="hidden" name="kode_unit" value="{{ $ppk->kode_unit }}">
    <input type="hidden" name="kode_anggaran" value="{{ $ppk->kode_anggaran }}">
    <input type="hidden" name="jangka_waktu" value="{{ $ppk->jangka_waktu }}">
    <input type="hidden" name="diajukan_tanggal" value="{{ $ppk->diajukan_tanggal->format('Y-m-d') }}">
    <input type="hidden" name="kembali_tanggal" value="{{ $ppk->kembali_tanggal->format('Y-m-d') }}">
    
    <!-- Hidden inputs untuk aktivitas -->
    @if($ppk->details && count($ppk->details) > 0)
        @foreach($ppk->details as $index => $detail)
        <input type="hidden" name="aktivitas[{{ $index }}][tanggal_aktivitas]" value="{{ $detail->tanggal_aktivitas }}">
        <input type="hidden" name="aktivitas[{{ $index }}][aktivitas]" value="{{ $detail->aktivitas }}">
        <input type="hidden" name="aktivitas[{{ $index }}][rencana]" value="{{ $detail->rencana }}">
        @endforeach
    @endif

    <div class="row">
        <!-- Form PPK -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>Informasi PPK
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_dokumen">No. Dokumen</label>
                                <input type="text" id="no_dokumen" class="form-control" value="{{ $ppk->no_dokumen }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="divisi">Divisi</label>
                                <input type="text" id="divisi" class="form-control" value="{{ $ppk->divisi }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_unit">Kode Unit</label>
                                <input type="text" id="kode_unit" class="form-control" value="{{ $ppk->kode_unit }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_anggaran">Kode Anggaran</label>
                                <input type="text" id="kode_anggaran" class="form-control" value="{{ $ppk->kode_anggaran }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jangka_waktu">Jangka Waktu</label>
                                <input type="text" id="jangka_waktu" class="form-control" value="{{ $ppk->jangka_waktu }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="diajukan_tanggal">Tanggal Pengajuan</label>
                                <input type="date" id="diajukan_tanggal" class="form-control" value="{{ $ppk->diajukan_tanggal->format('Y-m-d') }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kembali_tanggal">Tanggal Kembali</label>
                                <input type="date" id="kembali_tanggal" class="form-control" value="{{ $ppk->kembali_tanggal->format('Y-m-d') }}" readonly>
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
                                
                                @if($ppk->lampiran && count($ppk->lampiran) > 0)
                                <div class="mt-2">
                                    <label class="text-muted">File saat ini:</label>
                                    <div class="existing-files" id="existing-files">
                                        @foreach($ppk->lampiran as $index => $file)
                                        <div class="file-item d-inline-block mr-2 mb-1" data-file="{{ $file }}">
                                            <span class="badge badge-info">
                                                <i class="fas fa-file mr-1"></i>
                                                {{ basename($file) }}
                                                <span class="remove-existing-file ml-1" style="cursor: pointer;" title="Hapus file">
                                                    <i class="fas fa-times"></i>
                                                </span>
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>
                                    <small class="text-muted d-block">Klik tanda X untuk menghapus file. Upload file baru untuk menambah lampiran.</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Section -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-2"></i>Daftar Aktivitas
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Tanggal</th>
                                    <th width="45%">Aktivitas</th>
                                    <th width="30%">Rencana Biaya (Rp)</th>
                                </tr>
                            </thead>
                            <tbody id="aktivitas-table-body">
                                @if($ppk->details && count($ppk->details) > 0)
                                    @foreach($ppk->details as $index => $detail)
                                    <tr class="aktivitas-row">
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($detail->tanggal_aktivitas)->format('d/m/Y') }}</td>
                                        <td>{{ $detail->aktivitas }}</td>
                                        <td class="text-right">{{ number_format($detail->rencana, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada aktivitas</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 bg-light">
                        <div class="row">
                            <div class="col-sm-8">
                                <span class="text-muted">
                                    <i class="fas fa-list-ol mr-1"></i>
                                    Total aktivitas: <span id="total-aktivitas">{{ $ppk->details ? count($ppk->details) : 0 }}</span>
                                </span>
                            </div>
                            <div class="col-sm-4 text-right">
                                <strong class="text-primary">
                                    <i class="fas fa-calculator mr-1"></i>
                                    Total Nilai: Rp <span id="total-nilai">{{ number_format($ppk->details->sum('rencana'), 0, ',', '.') }}</span>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>Informasi PPK
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-light mb-3">
                        <span class="info-box-icon bg-{{ $ppk->status == 'draft' ? 'secondary' : ($ppk->status == 'submitted' ? 'warning' : 'success') }}">
                            <i class="fas fa-file-alt"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Status</span>
                            <span class="info-box-number">
                                <span class="badge badge-{{ $ppk->status == 'draft' ? 'secondary' : ($ppk->status == 'submitted' ? 'warning' : 'success') }}">
                                    {{ ucfirst($ppk->status) }}
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="info-box bg-light mb-3">
                        <span class="info-box-icon bg-info">
                            <i class="fas fa-clock"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Dibuat</span>
                            <span class="info-box-number text-sm">
                                {{ $ppk->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>

                    <div class="info-box bg-light mb-3">
                        <span class="info-box-icon bg-primary">
                            <i class="fas fa-user"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pembuat</span>
                            <span class="info-box-number text-sm">
                                {{ $ppk->creator->nama ?? 'Unknown' }}
                            </span>
                        </div>
                    </div>

                    <div class="info-box bg-light mb-3">
                        <span class="info-box-icon bg-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Catatan</span>
                            <ul class="info-box-more mb-0 pl-3">
                                <li>Hanya lampiran dokumen yang dapat diubah</li>
                                <li>Data PPK dan aktivitas bersifat read-only</li>
                                <li>Untuk perubahan data lain, hubungi administrator</li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Update Lampiran
                        </button>
                        <a href="{{ route('ppk.show', $ppk->id) }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
/* Custom styles untuk form PPK edit */
.form-control[readonly] {
    background-color: #f8f9fa;
    opacity: 1;
    cursor: not-allowed;
}

.file-item .remove-existing-file:hover {
    color: #dc3545 !important;
}

.file-preview-item {
    display: inline-block;
    margin: 2px;
    padding: 4px 8px;
    background: #e9ecef;
    border-radius: 4px;
    font-size: 12px;
    border: 1px solid #dee2e6;
}

.file-preview-item .remove-file {
    margin-left: 8px;
    color: #dc3545;
    cursor: pointer;
    font-weight: bold;
}

.file-preview-item .remove-file:hover {
    color: #c82333;
}

.custom-file-input:focus ~ .custom-file-label {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.info-box-more {
    font-size: 0.875rem;
    margin-top: 4px;
    line-height: 1.4;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let filesToDelete = [];

    // Initialize file input handling
    initializeFileHandling();

    // Handle existing file removal - tanpa konfirmasi
    $(document).on('click', '.remove-existing-file', function() {
        const fileItem = $(this).closest('.file-item');
        const fileName = fileItem.data('file');
        
        filesToDelete.push(fileName);
        fileItem.fadeOut(300, function() {
            $(this).remove();
        });
    });

    // Form submission - tanpa loading spinner berlebihan
    $('#ppk-form').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-save mr-1"></i> Updating...');

        const formData = new FormData(this);
        
        // Add files to delete
        filesToDelete.forEach(function(file) {
            formData.append('delete_files[]', file);
        });

        $.ajax({
            url: "{{ route('ppk.update', $ppk->id) }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showAlert('success', 'Berhasil', response.message);
                    if (response.redirect) {
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 1000);
                    } else {
                        // Refresh page to show updated files
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                } else {
                    showAlert('error', 'Gagal', response.message || 'Terjadi kesalahan saat mengupdate');
                    submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Update Lampiran');
                }
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat mengupdate';
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessages = [];
                    for (let field in errors) {
                        errorMessages.push(errors[field][0]);
                    }
                    message = errorMessages.join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                showAlert('error', 'Error', message);
                submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Update Lampiran');
            }
        });
    });

    // ===== FILE HANDLING =====
    function initializeFileHandling() {
        $('.custom-file-input').on('change', function() {
            handleFileSelection(this);
        });
    }

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

        // Handle new file removal - tanpa konfirmasi
        $('.remove-file').on('click', function() {
            input.value = '';
            $(input).next('.custom-file-label').text('Pilih file...');
            $('#file-preview').empty();
        });
    }

    // ===== UTILITY FUNCTIONS =====
    function showAlert(type, title, text = '') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: title,
                html: text,
                timer: type === 'success' ? 2000 : 4000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        } else {
            // Fallback to browser alert
            alert(title + (text ? ': ' + text : ''));
        }
    }
});
</script>
@endpush