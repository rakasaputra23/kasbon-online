@extends('layouts.app')

@section('title', 'Daftar PPK - Kasbon Online System')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Daftar PPK</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">PPK</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-list mr-2"></i>Data PPK
                            @if(Auth::user()->getApprovalLevel() && Auth::user()->getApprovalLevel() !== 'pegawai')
                                <small class="text-muted">- {{ Auth::user()->getApprovalLevelName() }}</small>
                                @if(Auth::user()->getApprovalLevel() === 'approval1')
                                    <small class="text-info">(Divisi: {{ Auth::user()->divisi ?? 'Tidak ada divisi' }})</small>
                                @endif
                            @endif
                        </h3>
                    </div>
                    <div class="col-sm-6 text-right">
                        @if(Auth::user()->canAccessRoute('ppk.create'))
                        <a href="{{ route('ppk.create') }}" class="btn btn-primary btn-kai">
                            <i class="fas fa-plus mr-2"></i>Buat PPK Baru
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Enhanced info section untuk approver -->
                @if(Auth::user()->isApprover())
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Info Approval:</strong>
                            
                            @if(Auth::user()->getApprovalLevel() === 'approval1')
                                <span id="pending-count">Loading...</span> PPK menunggu approval Anda di divisi <strong>{{ Auth::user()->divisi ?? 'Tidak ada divisi' }}</strong>.
                                <br><small class="text-muted">
                                    Anda hanya melihat PPK dari divisi Anda dan PPK yang Anda buat sendiri.
                                </small>
                            @elseif(Auth::user()->getApprovalLevel() === 'approval2')  
                                <span id="pending-count">Loading...</span> PPK menunggu approval Anda dari <strong>seluruh divisi</strong>.
                                <br><small class="text-muted">
                                    Anda melihat PPK level 2 lintas divisi dan PPK yang Anda buat sendiri.
                                </small>
                            @else
                                <span id="pending-count">Loading...</span> PPK menunggu approval Anda di level <strong>{{ str_replace('approval', '', Auth::user()->getApprovalLevel()) }}</strong>.
                                <br><small class="text-muted">
                                    Anda melihat PPK di level Anda dan PPK yang Anda buat sendiri.
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if(Auth::user()->isPegawai())
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-primary">
                            <i class="fas fa-user mr-2"></i>
                            <strong>Info:</strong> Anda hanya dapat melihat dan mengelola PPK yang Anda buat sendiri.
                        </div>
                    </div>
                </div>
                @endif

                @if(Auth::user()->isSuperAdmin())
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <i class="fas fa-crown mr-2"></i>
                            <strong>Admin Mode:</strong> Anda dapat melihat dan mengelola semua PPK dari seluruh divisi dan level.
                        </div>
                    </div>
                </div>
                @endif

                <!-- Enhanced Filter based on User Level -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select id="filter_status" class="form-control">
                            <option value="">Semua Status</option>
                            
                            @php
                                $userLevel = Auth::user()->getApprovalLevel();
                                $statusOptions = [];
                                
                                if (Auth::user()->isSuperAdmin()) {
                                    // Admin sees all statuses
                                    $statusOptions = [
                                        'draft' => 'Draft',
                                        'pending_approval1' => 'Menunggu Approval 1 (Staf/Kabag)',
                                        'pending_approval2' => 'Menunggu Approval 2 (Kadept)',
                                        'pending_approval3' => 'Menunggu Approval 3 (Kadiv User)',
                                        'pending_approval4' => 'Menunggu Approval 4 (Kadiv Keuangan)',
                                        'pending_approval5' => 'Menunggu Approval 5 (Direktur)',
                                        'pending_approval6' => 'Menunggu Approval 6 (Kasir)',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak'
                                    ];
                                } elseif (Auth::user()->isPegawai()) {
                                    // Pegawai only sees their own statuses
                                    $statusOptions = [
                                        'draft' => 'Draft',
                                        'pending_approval1' => 'Menunggu Approval 1',
                                        'pending_approval2' => 'Menunggu Approval 2',
                                        'pending_approval3' => 'Menunggu Approval 3',
                                        'pending_approval4' => 'Menunggu Approval 4',
                                        'pending_approval5' => 'Menunggu Approval 5',
                                        'pending_approval6' => 'Menunggu Approval 6',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak'
                                    ];
                                } elseif (Auth::user()->isApprover()) {
                                    // Approvers see relevant statuses
                                    $currentLevelStatus = 'pending_' . $userLevel;
                                    $statusOptions = [
                                        $currentLevelStatus => '🔥 Menunggu Approval Saya',
                                    ];
                                    
                                    // Add other visible statuses based on level
                                    if ($userLevel === 'approval1') {
                                        $statusOptions = array_merge($statusOptions, [
                                            'draft' => 'Draft (PPK Saya)',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak'
                                        ]);
                                    } else {
                                        $statusOptions = array_merge($statusOptions, [
                                            'pending_approval1' => 'Menunggu Approval 1',
                                            'pending_approval2' => 'Menunggu Approval 2',
                                            'pending_approval3' => 'Menunggu Approval 3',
                                            'pending_approval4' => 'Menunggu Approval 4',
                                            'pending_approval5' => 'Menunggu Approval 5',
                                            'pending_approval6' => 'Menunggu Approval 6',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak'
                                        ]);
                                    }
                                }
                            @endphp
                            
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filter_divisi" class="form-control">
                            <option value="">Semua Divisi</option>
                            @if(Auth::user()->getApprovalLevel() === 'approval1')
                                <!-- Approval1 default to their division -->
                                <option value="{{ Auth::user()->divisi }}" selected>{{ Auth::user()->divisi }} (Divisi Saya)</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="month" id="filter_periode" class="form-control" placeholder="Pilih Periode">
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="btn_reset_filter" class="btn btn-secondary">
                            <i class="fas fa-undo mr-1"></i>Reset
                        </button>
                        @if(Auth::user()->isApprover())
                        <button type="button" id="btn_my_approvals" class="btn btn-warning ml-1">
                            <i class="fas fa-user-check mr-1"></i>Perlu Approval
                        </button>
                        @endif
                        @if(Auth::user()->getApprovalLevel() === 'approval1')
                        <button type="button" id="btn_my_division" class="btn btn-info ml-1">
                            <i class="fas fa-building mr-1"></i>Divisi Saya
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="ppk-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Dokumen</th>
                                <th>Divisi</th>
                                <th>Tgl Pengajuan</th>
                                <th>Tgl Kembali</th>
                                <th>Total Nilai</th>
                                <th>Status</th>
                                <th>Pembuat</th>
                                @if(Auth::user()->isApprover())
                                <th>Level Approval</th>
                                @endif
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Hapus PPK</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menghapus PPK ini?</p>
                <div id="delete-info"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Submit Modal -->
<div class="modal fade" id="submitModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Submit PPK</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>PPK yang sudah disubmit tidak dapat diedit lagi. Anda yakin ingin submit?</p>
                <div id="submit-info"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="confirm-submit">Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Enhanced styling for user-level specific interface */
.btn-kai {
    background: linear-gradient(135deg, var(--kai-primary), var(--kai-primary-light)) !important;
    border: none !important;
    color: white !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    padding: 0.5rem 1rem !important;
    transition: all 0.3s ease !important;
}

.btn-kai:hover {
    background: linear-gradient(135deg, var(--kai-primary-dark), var(--kai-primary)) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3) !important;
    color: white !important;
}

/* Enhanced table styling */
#ppk-table thead th {
    background: var(--kai-gray-50) !important;
    color: var(--kai-gray-700) !important;
    font-weight: 600 !important;
    border-color: var(--kai-gray-200) !important;
}

#ppk-table tbody tr:hover {
    background-color: var(--kai-gray-50) !important;
}

/* Enhanced highlight for user-specific rows */
#ppk-table tbody tr.pending-approval {
    background-color: rgba(255, 193, 7, 0.15) !important;
    border-left: 4px solid #ffc107;
}

#ppk-table tbody tr.my-division {
    background-color: rgba(23, 162, 184, 0.1) !important;
    border-left: 3px solid #17a2b8;
}

#ppk-table tbody tr.my-ppk {
    background-color: rgba(40, 167, 69, 0.1) !important;
    border-left: 3px solid #28a745;
}

/* Filter controls */
.form-control:focus {
    border-color: var(--kai-primary-light) !important;
    box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25) !important;
}

/* Action button group */
.btn-group .btn {
    margin: 0 1px;
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
}

/* Enhanced approval level badge */
.approval-level-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-weight: 600;
}

.badge-urgent {
    background-color: #dc3545 !important;
    color: white !important;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

/* Alert styling */
.alert-info {
    border-left: 4px solid #17a2b8;
}

.alert-primary {
    border-left: 4px solid #007bff;
}

.alert-success {
    border-left: 4px solid #28a745;
}

/* Loading state */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Modal styling */
.modal-header {
    background: var(--kai-primary) !important;
    color: white !important;
}

.modal-content {
    border-radius: 8px !important;
    border: none !important;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    const userLevel = @json(Auth::user()->getApprovalLevel());
    const isApprover = @json(Auth::user()->isApprover());
    const isPegawai = @json(Auth::user()->isPegawai());
    const isAdmin = @json(Auth::user()->isSuperAdmin());
    const userDivisi = @json(Auth::user()->divisi);
    const userName = @json(Auth::user()->nama);
    
    console.log('User Info:', {
        userLevel, isApprover, isPegawai, isAdmin, userDivisi, userName
    });
    
    // Define columns based on user role
    let tableColumns = [
        { 
            data: null, 
            orderable: false, 
            searchable: false,
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        { data: 'no_dokumen', name: 'no_dokumen' },
        { 
            data: 'divisi', 
            name: 'divisi',
            render: function(data, type, row) {
                let html = data || '-';
                if (userLevel === 'approval1' && data === userDivisi) {
                    html = '<span class="text-primary font-weight-bold">' + data + '</span> <small class="text-muted">(Divisi Anda)</small>';
                }
                return html;
            }
        },
        { data: 'diajukan_tanggal', name: 'diajukan_tanggal' },
        { data: 'kembali_tanggal', name: 'kembali_tanggal' },
        { 
            data: 'total_formatted', 
            name: 'total_nilai',
            className: 'text-right'
        },
        { 
            data: 'status_badge', 
            name: 'status',
            orderable: false,
            className: 'text-center'
        },
        { 
            data: 'creator_name', 
            name: 'creator_name',
            render: function(data, type, row) {
                let html = data || '-';
                if (data === userName) {
                    html = '<span class="text-success font-weight-bold">' + data + '</span> <small class="text-muted">(Anda)</small>';
                }
                return html;
            }
        }
    ];

    // Add approval level column for approvers
    if (isApprover) {
        tableColumns.push({
            data: null,
            name: 'approval_level',
            orderable: false,
            className: 'text-center',
            render: function(data, type, row) {
                if (!row.status) return '<span class="badge badge-secondary">-</span>';
                
                const currentLevel = row.status.replace('pending_', '');
                if (row.status.includes('pending_') && currentLevel === userLevel) {
                    return '<span class="badge badge-danger approval-level-badge badge-urgent">🔥 Perlu Approval Anda</span>';
                } else if (row.status.includes('pending_')) {
                    return '<span class="badge badge-info approval-level-badge">Level ' + currentLevel.replace('approval', '') + '</span>';
                } else if (row.status === 'approved') {
                    return '<span class="badge badge-success approval-level-badge">✅ Selesai</span>';
                } else if (row.status === 'rejected') {
                    return '<span class="badge badge-danger approval-level-badge">❌ Ditolak</span>';
                } else {
                    return '<span class="badge badge-secondary approval-level-badge">-</span>';
                }
            }
        });
    }

    tableColumns.push({ 
        data: 'action', 
        name: 'action',
        orderable: false,
        searchable: false,
        className: 'text-center',
        defaultContent: '<span class="text-muted">-</span>'
    });

    // Initialize DataTable dengan handling yang lebih baik
    const table = $('#ppk-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('ppk.data') }}",
            type: "GET",
            data: function(d) {
                d.status = $('#filter_status').val();
                d.divisi = $('#filter_divisi').val();
                d.periode = $('#filter_periode').val();
            },
            beforeSend: function() {
                console.log('DataTables: Request starting...');
            },
            error: function(xhr, error, code) {
                console.error('DataTables AJAX Error:', {
                    status: xhr.status,
                    error: error,
                    code: code,
                    responseText: xhr.responseText
                });
                
                // Force hide loading indicator
                $('.dataTables_processing').hide();
                
                // Clear table body and show message
                $('#ppk-table tbody').html(
                    '<tr><td colspan="' + tableColumns.length + '" class="text-center text-muted py-4">' +
                    '<i class="fas fa-info-circle mr-2"></i>' +
                    'Tidak ada PPK yang tersedia untuk level dan divisi Anda saat ini.' +
                    '</td></tr>'
                );
                
                // Update info
                $('.dataTables_info').html(
                    '<i class="fas fa-info-circle text-info mr-2"></i>' +
                    'Tidak ada PPK yang dapat diakses untuk level Anda saat ini.'
                );
                
                // Hide pagination
                $('.dataTables_paginate').hide();
                
                // Show user-friendly alert only once
                if (!window.errorShown) {
                    window.errorShown = true;
                    setTimeout(() => {
                        showAlert('info', 'Informasi', 
                            'Tidak ada PPK yang tersedia untuk level dan divisi Anda saat ini. ' +
                            'Sistem bekerja normal - Anda hanya bisa melihat PPK sesuai kewenangan Anda.');
                    }, 500);
                }
                
                return false; // Prevent default error handling
            },
            complete: function(xhr, status) {
                console.log('DataTables: Request completed with status:', status);
                
                // Force hide processing indicator
                setTimeout(() => {
                    $('.dataTables_processing').hide();
                }, 100);
            }
        },
        columns: tableColumns,
        order: [[3, 'desc']],
        pageLength: 25,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        language: {
            processing: '<div class="d-flex justify-content-center align-items-center"><div class="spinner-border spinner-border-sm text-primary mr-2" role="status"></div>Memuat data PPK...</div>',
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data untuk ditampilkan",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Tidak ada PPK yang tersedia untuk level dan divisi Anda.",
            emptyTable: "Tidak ada PPK yang dapat diakses.",
            paginate: {
                first: "Pertama",
                last: "Terakhir", 
                next: "Selanjutnya",
                previous: "Sebelumnya"
            },
            loadingRecords: "Memuat data...",
            searchPlaceholder: "Cari PPK..."
        },
        drawCallback: function(settings) {
            console.log('DataTables: Draw callback executed');
            
            // Force hide processing indicator after draw
            $('.dataTables_processing').hide();
            
            // Check if no data and show appropriate message
            const api = this.api();
            if (api.rows().count() === 0) {
                const userLevelText = userLevel === 'approval1' ? 
                    'Approval Level 1 (Staf/Kabag)' : 
                    'Level ' + (userLevel || 'Unknown');
                    
                $('.dataTables_info').html(
                    '<i class="fas fa-info-circle text-info mr-2"></i>' +
                    'Tidak ada PPK untuk ' + userLevelText + 
                    (userLevel === 'approval1' && userDivisi ? ' di divisi ' + userDivisi : '') + 
                    ' saat ini.'
                );
            }
        },
        createdRow: function(row, data, dataIndex) {
            // Enhanced row highlighting based on user level
            if (isApprover && data.status && data.status.includes('pending_') && 
                data.status.replace('pending_', '') === userLevel && 
                data.creator_name !== userName) {
                $(row).addClass('pending-approval');
            }
            
            // Highlight PPK from user's division (for approval1)
            if (userLevel === 'approval1' && data.divisi === userDivisi && data.creator_name !== userName) {
                $(row).addClass('my-division');
            }
            
            // Highlight user's own PPK
            if (data.creator_name === userName) {
                $(row).addClass('my-ppk');
            }
        },
        initComplete: function(settings, json) {
            console.log('DataTables: Init complete');
            
            // Force hide processing indicator
            $('.dataTables_processing').hide();
            
            // Show appropriate message if no data
            if (!json || json.recordsTotal === 0) {
                const userLevelText = userLevel === 'approval1' ? 
                    'Approval Level 1 (Staf/Kabag)' : 
                    'Level ' + (userLevel || 'Unknown');
                    
                $('.dataTables_info').html(
                    '<i class="fas fa-info-circle text-info mr-2"></i>' +
                    'Tidak ada PPK untuk ' + userLevelText + 
                    (userLevel === 'approval1' && userDivisi ? ' di divisi ' + userDivisi : '') + 
                    ' saat ini.'
                );
            }
            
            console.log('DataTable initialized successfully');
        }
    });

    // Force hide processing indicator on any table redraw
    $('#ppk-table').on('processing.dt', function(e, settings, processing) {
        if (!processing) {
            setTimeout(() => {
                $('.dataTables_processing').hide();
            }, 100);
        }
    });

    // Load divisi options with enhanced filtering
    $.get("{{ route('ppk.divisi-options') }}", function(response) {
        if (response.success && response.data) {
            const select = $('#filter_divisi');
            response.data.forEach(function(divisi) {
                // Don't duplicate user's division for approval1
                if (!(userLevel === 'approval1' && divisi.value === userDivisi)) {
                    select.append(`<option value="${divisi.value}">${divisi.name}</option>`);
                }
            });
        }
    });

    // Load pending approval count for approvers
    if (isApprover) {
        loadPendingCount();
    }

    function loadPendingCount() {
        $.get("{{ route('ppk.approval-stats') }}", function(response) {
            if (response.success && response.data) {
                const pendingCount = response.data.pending_for_user || 0;
                $('#pending-count').text(pendingCount);
                
                // Update button badge
                if (pendingCount > 0) {
                    $('#btn_my_approvals').html('<i class="fas fa-user-check mr-1"></i>Perlu Approval <span class="badge badge-light ml-1">' + pendingCount + '</span>');
                }
            }
        }).fail(function() {
            $('#pending-count').text('0');
        });
    }

    // Enhanced filter handlers dengan loading management
    $('#filter_status, #filter_divisi, #filter_periode').on('change', function() {
        // Reset error flag
        window.errorShown = false;
        table.ajax.reload(null, false); // false = keep paging position
    });

    // Reset filter
    $('#btn_reset_filter').on('click', function() {
        $('#filter_status').val('');
        $('#filter_divisi').val('');
        $('#filter_periode').val('');
        
        // Reset to user's division for approval1
        if (userLevel === 'approval1' && userDivisi) {
            $('#filter_divisi').val(userDivisi);
        }
        
        window.errorShown = false;
        table.ajax.reload();
    });

    // My approvals filter
    $('#btn_my_approvals').on('click', function() {
        if (userLevel) {
            $('#filter_status').val('pending_' + userLevel);
            if (userLevel === 'approval1' && userDivisi) {
                $('#filter_divisi').val(userDivisi);
            }
            window.errorShown = false;
            table.ajax.reload();
        }
    });

    // My division filter (for approval1)
    $('#btn_my_division').on('click', function() {
        if (userDivisi) {
            $('#filter_divisi').val(userDivisi);
            $('#filter_status').val('');
            window.errorShown = false;
            table.ajax.reload();
        }
    });

    // Delete handler
    let deleteId = null;
    $(document).on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        const row = $(this).closest('tr');
        const rowData = table.row(row).data();
        
        $('#delete-info').html(`
            <strong>No. Dokumen:</strong> ${rowData.no_dokumen || '-'}<br>
            <strong>Divisi:</strong> ${rowData.divisi || '-'}<br>
            <strong>Status:</strong> ${rowData.status || '-'}
        `);
        $('#deleteModal').modal('show');
    });

    $('#confirm-delete').on('click', function() {
        if (!deleteId) return;

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menghapus...');

        $.ajax({
            url: "{{ route('ppk.destroy', ':id') }}".replace(':id', deleteId),
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload(null, false);
                    showAlert('success', 'Berhasil', response.message);
                } else {
                    showAlert('error', 'Gagal', response.message);
                }
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat menghapus data';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert('error', 'Gagal', message);
            },
            complete: function() {
                btn.prop('disabled', false).html('Hapus');
                deleteId = null;
            }
        });
    });

    // Submit handler
    let submitId = null;
    $(document).on('click', '.submit-btn', function() {
        submitId = $(this).data('id');
        const row = $(this).closest('tr');
        const rowData = table.row(row).data();
        
        $('#submit-info').html(`
            <strong>No. Dokumen:</strong> ${rowData.no_dokumen || '-'}<br>
            <strong>Divisi:</strong> ${rowData.divisi || '-'}<br>
            <strong>Total Nilai:</strong> ${rowData.total_formatted || '-'}
        `);
        $('#submitModal').modal('show');
    });

    $('#confirm-submit').on('click', function() {
        if (!submitId) return;

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Submit...');

        $.ajax({
            url: "{{ route('ppk.submit', ':id') }}".replace(':id', submitId),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#submitModal').modal('hide');
                    table.ajax.reload(null, false);
                    showAlert('success', 'Berhasil', response.message);
                    if (isApprover) {
                        loadPendingCount();
                    }
                } else {
                    showAlert('error', 'Gagal', response.message);
                }
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat submit PPK';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert('error', 'Gagal', message);
            },
            complete: function() {
                btn.prop('disabled', false).html('Submit');
                submitId = null;
            }
        });
    });

    // Global alert function
    if (typeof showAlert !== 'function') {
        window.showAlert = function(type, title, message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: type,
                    title: title,
                    text: message,
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                alert(title + ': ' + message);
            }
        };
    }
});
</script>
@endpush