@extends('layouts.app')

@section('title', 'Detail PPK - Kasbon Online System')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Detail PPK</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ppk.index') }}">PPK</a></li>
            <li class="breadcrumb-item active">{{ $ppk->no_dokumen }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Main Content -->
    <div class="col-md-8">
        <!-- PPK Information Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-alt mr-2"></i>Informasi PPK
                </h3>
                <div class="card-tools">
                    <span class="badge badge-{{ $ppk->status_color }} badge-lg">{{ $ppk->status_label }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>No. Dokumen:</strong></td>
                                <td>{{ $ppk->no_dokumen }}</td>
                            </tr>
                            <tr>
                                <td><strong>Divisi:</strong></td>
                                <td>{{ $ppk->divisi }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kode Unit:</strong></td>
                                <td>{{ $ppk->kode_unit ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kode Anggaran:</strong></td>
                                <td>{{ $ppk->kode_anggaran ?: '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Tgl Pengajuan:</strong></td>
                                <td>{{ $ppk->diajukan_tanggal->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tgl Kembali:</strong></td>
                                <td>{{ $ppk->kembali_tanggal->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jangka Waktu:</strong></td>
                                <td>{{ $ppk->jangka_waktu ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Nilai:</strong></td>
                                <td><strong class="text-primary">Rp {{ number_format($ppk->total_nilai, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($ppk->lampiran)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6><strong>Lampiran Dokumen:</strong></h6>
                        <div class="attachments">
                            @foreach($ppk->lampiran as $index => $file)
                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-outline-info btn-sm mr-2 mb-2">
                                <i class="fas fa-file mr-1"></i> {{ basename($file) }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Aktivitas Details Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2"></i>Detail Aktivitas
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Tanggal</th>
                                <th width="60%">Aktivitas</th>
                                <th width="20%">Rencana Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ppk->details as $detail)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($detail->tanggal_aktivitas)->format('d/m/Y') }}</td>
                                <td>{{ $detail->aktivitas }}</td>
                                <td class="text-right">Rp {{ number_format($detail->rencana, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada data aktivitas</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($ppk->details->count() > 0)
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="3" class="text-right">Total:</th>
                                <th class="text-right">Rp {{ number_format($ppk->total_nilai, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Approval History Card -->
        @if($ppk->approvalLogs->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2"></i>Riwayat Approval
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="15%">Tanggal</th>
                                <th width="15%">Level</th>
                                <th width="20%">User</th>
                                <th width="10%">Aksi</th>
                                <th width="40%">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ppk->approvalLogs as $log)
                            <tr>
                                <td>{{ $log->tanggal_aksi->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $log->level)) }}</span>
                                </td>
                                <td>
                                    <strong>{{ $log->nama_user }}</strong><br>
                                    <small class="text-muted">{{ $log->nip_user }}</small>
                                </td>
                                <td>
                                    @if($log->aksi == 'approve')
                                        <span class="badge badge-success">Approve</span>
                                    @else
                                        <span class="badge badge-danger">Reject</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->catatan)
                                        <p class="mb-1">{{ $log->catatan }}</p>
                                    @endif
                                    
                                    @if($log->disposisi_dana_tersedia || $log->disposisi_dana_terpakai || $log->disposisi_sisa_anggaran)
                                        <div class="mt-2">
                                            <small class="text-muted"><strong>Disposisi Anggaran:</strong></small>
                                            @if($log->disposisi_dana_tersedia)
                                                <br><small>Dana Tersedia: Rp {{ number_format($log->disposisi_dana_tersedia, 0, ',', '.') }}</small>
                                            @endif
                                            @if($log->disposisi_dana_terpakai)
                                                <br><small>Dana Terpakai: Rp {{ number_format($log->disposisi_dana_terpakai, 0, ',', '.') }}</small>
                                            @endif
                                            @if($log->disposisi_sisa_anggaran)
                                                <br><small>Sisa Anggaran: Rp {{ number_format($log->disposisi_sisa_anggaran, 0, ',', '.') }}</small>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if(!$log->catatan && !$log->disposisi_dana_tersedia)
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Action Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tools mr-2"></i>Aksi
                </h3>
            </div>
            <div class="card-body">
                <div class="btn-group-vertical w-100" role="group">
                    
                    @php
                        $user = Auth::user();
                        $currentLevel = $ppk->getCurrentApprovalLevel();
                        $userLevel = $user->getApprovalLevel();
                    @endphp

                    <!-- Edit Button -->
                    @if($ppk->canBeEdited() && $ppk->dibuat_oleh == $user->id)
                    <a href="{{ route('ppk.edit', $ppk->id) }}" class="btn btn-warning mb-2">
                        <i class="fas fa-edit mr-1"></i> Edit PPK
                    </a>
                    @endif

                    <!-- Submit Button -->
                    @if($ppk->status === 'draft' && $ppk->dibuat_oleh == $user->id)
                    <button type="button" class="btn btn-success mb-2 submit-btn" data-id="{{ $ppk->id }}">
                        <i class="fas fa-paper-plane mr-1"></i> Submit untuk Approval
                    </button>
                    @endif

                    <!-- FIXED: Approve & Reject Buttons Logic -->
                    @if($ppk->canBeApproved() && $user->canApprovePpk($ppk))
                        @if($currentLevel === 'approval2' && $userLevel === 'approval2')
                        <!-- Approval Level 2: Kepala Departemen dengan Disposisi -->
                        <button type="button" class="btn btn-primary mb-2 approve-disposisi-btn" data-id="{{ $ppk->id }}">
                            <i class="fas fa-check mr-1"></i> Approve & Isi Disposisi Anggaran
                        </button>
                        @else
                        <!-- Approval Level Lainnya: Default Approval -->
                        <button type="button" class="btn btn-primary mb-2 approve-btn" data-id="{{ $ppk->id }}">
                            <i class="fas fa-check mr-1"></i> Approve
                        </button>
                        @endif
                        
                        <!-- Reject Button -->
                        <button type="button" class="btn btn-danger mb-2 reject-btn" data-id="{{ $ppk->id }}">
                            <i class="fas fa-times mr-1"></i> Reject
                        </button>
                    @endif

                    <!-- Edit Lampiran Button -->
                    @if($ppk->canEditLampiran($user))
                    <a href="{{ route('ppk.lampiran.edit', $ppk->id) }}" class="btn btn-info mb-2">
                        <i class="fas fa-paperclip mr-1"></i> Edit
                    </a>
                    @endif

                    <!-- Delete Button -->
                    @if($ppk->canBeDeleted() && $ppk->dibuat_oleh == $user->id)
                    <button type="button" class="btn btn-danger mb-2 delete-btn" data-id="{{ $ppk->id }}">
                        <i class="fas fa-trash mr-1"></i> Hapus PPK
                    </button>
                    @endif

                    <!-- PDF Button - Only for approved PPK -->
                    @if($ppk->status === 'approved' && $user->canViewPpk($ppk))
                    <a href="{{ route('ppk.pdf', $ppk->id) }}" class="btn btn-secondary mb-2" target="_blank">
                        <i class="fas fa-file-pdf mr-1"></i> Download PDF
                    </a>
                    @endif

                    <!-- Back Button -->
                    <a href="{{ route('ppk.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Tambahan
                </h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-light mb-3">
                    <div class="info-box-content">
                        <span class="info-box-text">Pembuat</span>
                        <span class="info-box-number text-sm">
                            {{ $ppk->creator->nama ?? 'Unknown' }}<br>
                            <small class="text-muted">{{ $ppk->creator->nip ?? '' }}</small>
                        </span>
                    </div>
                </div>

                <div class="info-box bg-light mb-3">
                    <div class="info-box-content">
                        <span class="info-box-text">Dibuat</span>
                        <span class="info-box-number text-sm">
                            {{ $ppk->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>

                <div class="info-box bg-light mb-3">
                    <div class="info-box-content">
                        <span class="info-box-text">Terakhir Update</span>
                        <span class="info-box-number text-sm">
                            {{ $ppk->updated_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>

                <div class="info-box bg-light">
                    <div class="info-box-content">
                        <span class="info-box-text">Jumlah Aktivitas</span>
                        <span class="info-box-number">{{ $ppk->details->count() }}</span>
                    </div>
                </div>

                @if($user->isApprover() && $ppk->canBeApproved())
                <!-- Approval Info -->
                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-info-circle mr-1"></i> Info Approval:</h6>
                    <small>
                        <strong>Current Level:</strong> {{ $currentLevel ? ucfirst(str_replace('_', ' ', $currentLevel)) : '-' }}<br>
                        <strong>Your Level:</strong> {{ $userLevel ? ucfirst(str_replace('_', ' ', $userLevel)) : '-' }}<br>
                        @if($currentLevel === $userLevel)
                            <span class="text-success"><i class="fas fa-check mr-1"></i>PPK ini menunggu approval Anda</span>
                        @else
                            <span class="text-muted">PPK ini belum sampai level Anda</span>
                        @endif
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('ppk.modals.modal-approval-default')
@include('ppk.modals.modal-approval-disposisi') 
@include('ppk.modals.modal-reject')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const ppkId = {{ $ppk->id }};

    // CSRF Token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Submit handler
    $('.submit-btn').on('click', function() {
        const btn = $(this);
        showConfirm('Konfirmasi Submit', 'PPK yang sudah disubmit tidak dapat diedit lagi. Yakin ingin melanjutkan?', function() {
            submitPpk(btn);
        });
    });

    // Approve handler (default)
    $('.approve-btn').on('click', function() {
        console.log('Approve button clicked - showing modal');
        $('#approvalModal').modal('show');
    });

    // Approve with disposisi handler
    $('.approve-disposisi-btn').on('click', function() {
        console.log('Approve disposisi button clicked - showing modal');
        $('#approvalDisposisiModal').modal('show');
    });

    // Reject handler
    $('.reject-btn').on('click', function() {
        console.log('Reject button clicked - showing modal');
        $('#rejectModal').modal('show');
    });

    // Delete handler
    $('.delete-btn').on('click', function() {
        const btn = $(this);
        showConfirm('Konfirmasi Hapus', 'Yakin ingin menghapus PPK ini? Tindakan ini tidak dapat dibatalkan.', function() {
            deletePpk(btn);
        });
    });

    function submitPpk(btn) {
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Submit...');

        $.ajax({
            url: "{{ route('ppk.submit', $ppk->id) }}",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', 'Berhasil', response.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('error', 'Gagal', response.message);
                    btn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                console.error('Submit error:', xhr);
                const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat submit PPK';
                showAlert('error', 'Gagal', message);
                btn.prop('disabled', false).html(originalText);
            }
        });
    }

    function deletePpk(btn) {
        const originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menghapus...');

        $.ajax({
            url: "{{ route('ppk.destroy', $ppk->id) }}",
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', 'Berhasil', response.message);
                    setTimeout(() => {
                        window.location.href = "{{ route('ppk.index') }}";
                    }, 1500);
                } else {
                    showAlert('error', 'Gagal', response.message);
                    btn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                console.error('Delete error:', xhr);
                const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus PPK';
                showAlert('error', 'Gagal', message);
                btn.prop('disabled', false).html(originalText);
            }
        });
    }

    // Global alert function
    if (typeof showAlert !== 'function') {
        window.showAlert = function(type, title, message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: type,
                    title: title,
                    text: message,
                    timer: type === 'success' ? 2000 : 4000,
                    showConfirmButton: type === 'error'
                });
            } else {
                alert(title + ': ' + message);
            }
        };
    }

    // Global confirm function
    if (typeof showConfirm !== 'function') {
        window.showConfirm = function(title, message, callback) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: title,
                    text: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        callback();
                    }
                });
            } else {
                if (confirm(message)) {
                    callback();
                }
            }
        };
    }
});
</script>
@endpush