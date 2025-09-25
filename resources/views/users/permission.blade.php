@extends('layouts.app')

@section('title', 'Permission Management - Kasbon System')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Permission Management</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Home
                </a>
            </li>
            <li class="breadcrumb-item active">Permission</li>
        </ol>
    </div>
</div>
@endsection

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">

<style>
.permission-management {
    font-family: 'Inter', sans-serif;
}

.permission-management .btn-primary {
    background: linear-gradient(135deg, #1E40AF, #3B82F6);
    border: none;
    border-radius: 6px;
    font-weight: 600;
}

.permission-management .btn-primary:hover {
    background: linear-gradient(135deg, #1E3A8A, #1E40AF);
    transform: translateY(-1px);
}

.permission-management .btn-group .btn {
    border-radius: 4px;
    font-size: 0.75rem;
    padding: 0.375rem 0.5rem;
    margin: 0 1px;
}

.permission-management .table thead th {
    background: #F9FAFB;
    color: #374151;
    font-weight: 600;
    font-size: 0.875rem;
}

.permission-management .table tbody tr:hover {
    background: #F9FAFB;
}

.permission-management .form-control:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 0.1rem rgba(59, 130, 246, 0.25);
}

.permission-management .dataTables_wrapper .dataTables_paginate .paginate_button.current,
.permission-management .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #1E40AF !important;
    border-color: #1E40AF !important;
    color: white !important;
}

.permission-management .modal-content {
    border-radius: 8px;
    border: none;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.permission-management .modal-header {
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
    border-radius: 8px 8px 0 0;
}

.permission-management .modal-title {
    font-weight: 600;
    color: #1E293B;
}
</style>
@endpush

@section('content')
@php
    Auth::user()->refreshRelations();
    $canCreate = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('permissions.store');
    $canEdit = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('permissions.update');
    $canDelete = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('permissions.destroy');
    $canView = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('permissions.show');
@endphp

<div class="permission-management">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-key mr-2"></i>Data Permissions
                    </h3>
                    <div class="card-tools">
                        @if($canCreate)
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#permissionModal">
                            <i class="fas fa-plus mr-1"></i>Tambah Permission
                        </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="permissionTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Route Name</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Permission -->
    <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionModalLabel">Tambah Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="permissionForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="route_name" class="form-label">Route Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="route_name" name="route_name" required>
                            <div class="form-text">Contoh: user.index, user.store, dashboard</div>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi" required>
                            <div class="form-text">Deskripsi yang mudah dipahami tentang permission ini</div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Permission -->
    <div class="modal fade" id="detailPermissionModal" tabindex="-1" aria-labelledby="detailPermissionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailPermissionModalLabel">Detail Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailPermissionContent">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Permission flags
    const permissions = {
        canCreate: {{ $canCreate ? 'true' : 'false' }},
        canEdit: {{ $canEdit ? 'true' : 'false' }},
        canDelete: {{ $canDelete ? 'true' : 'false' }},
        canView: {{ $canView ? 'true' : 'false' }}
    };

    // Initialize DataTables
    let table = $('#permissionTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("permissions.getData") }}',
            type: 'GET'
        },
        columns: [
            { data: 'route_name', name: 'route_name' },
            { data: 'deskripsi', name: 'deskripsi' },
            { 
                data: 'created_at', 
                name: 'created_at',
                render: function(data) {
                    if (!data) return '-';
                    return new Date(data).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit', 
                        year: 'numeric'
                    });
                }
            },
            {
                data: 'id',
                render: function(data, type, row) {
                    let buttons = '<div class="btn-group" role="group">';
                    if (permissions.canView) {
                        buttons += `<button type="button" class="btn btn-sm btn-info" onclick="viewPermission(${data})" title="Detail">
                                      <i class="fas fa-eye"></i>
                                   </button>`;
                    }
                    if (permissions.canEdit) {
                        buttons += `<button type="button" class="btn btn-sm btn-warning" onclick="editPermission(${data})" title="Edit">
                                      <i class="fas fa-edit"></i>
                                   </button>`;
                    }
                    if (permissions.canDelete) {
                        buttons += `<button type="button" class="btn btn-sm btn-danger" onclick="deletePermission(${data})" title="Hapus">
                                      <i class="fas fa-trash"></i>
                                   </button>`;
                    }
                    buttons += '</div>';
                    return (!permissions.canView && !permissions.canEdit && !permissions.canDelete) ? 
                           '<span class="text-muted">-</span>' : buttons;
                },
                orderable: false,
                searchable: false,
                width: '120px'
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        },
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        order: [[0, 'asc']]
    });

    // Form submission
    $('#permissionForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let url = $('#permissionForm').data('action') || '{{ route("permissions.store") }}';
        let method = $('#permissionForm').data('method') || 'POST';
        
        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...').prop('disabled', true);

        clearValidationErrors();

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    closeModal();
                    table.ajax.reload(null, false);
                    showSuccessAlert(response.message);
                    resetForm();
                } else {
                    showErrorAlert(response.message || 'Terjadi kesalahan');
                }
            },
            error: function(xhr) {
                handleFormErrors(xhr);
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Reset modal when closed
    $('#permissionModal').on('hidden.bs.modal', resetForm);

    // Clear validation errors on input
    $(document).on('change input', '.form-control', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });
});

// Utility functions
function clearValidationErrors() {
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
}

function closeModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('permissionModal'));
    if (modal) modal.hide();
}

function showSuccessAlert(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
        background: '#d1fae5',
        color: '#065f46'
    });
}

function showErrorAlert(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: message,
        confirmButtonColor: '#dc3545'
    });
}

function handleFormErrors(xhr) {
    if (xhr.status === 422) {
        const errors = xhr.responseJSON.errors;
        $.each(errors, function(key, value) {
            const field = $(`[name="${key}"]`);
            field.addClass('is-invalid');
            field.siblings('.invalid-feedback').text(value[0]);
        });
        showErrorAlert('Mohon periksa kembali data yang dimasukkan');
    } else {
        const message = xhr.responseJSON?.message || 'Terjadi kesalahan pada server';
        showErrorAlert(message);
    }
}

function resetForm() {
    $('#permissionForm')[0].reset();
    $('#permissionModalLabel').text('Tambah Permission');
    $('#permissionForm').removeData('action').removeData('method');
    clearValidationErrors();
}

function editPermission(id) {
    if (!{{ $canEdit ? 'true' : 'false' }}) {
        showErrorAlert('Anda tidak memiliki izin untuk mengedit data.');
        return;
    }

    $.get(`{{ url('permissions') }}/${id}`)
    .done(function(response) {
        if (response.success) {
            let data = response.data;
            $('#permissionModalLabel').text('Edit Permission');
            $('#permissionForm').data('action', `{{ url('permissions') }}/${id}`).data('method', 'PUT');
            
            $('#route_name').val(data.route_name);
            $('#deskripsi').val(data.deskripsi);
            
            new bootstrap.Modal(document.getElementById('permissionModal')).show();
        } else {
            showErrorAlert(response.message || 'Gagal memuat data permission');
        }
    })
    .fail(function(xhr) {
        showErrorAlert(xhr.responseJSON?.message || 'Gagal memuat data permission');
    });
}

function viewPermission(id) {
    if (!{{ $canView ? 'true' : 'false' }}) {
        showErrorAlert('Anda tidak memiliki izin untuk melihat detail data.');
        return;
    }

    $.get(`{{ url('permissions') }}/${id}`)
    .done(function(response) {
        if (response.success) {
            let data = response.data;
            
            const formatDate = (dateStr) => {
                if (!dateStr) return '-';
                try {
                    return new Date(dateStr).toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } catch (e) {
                    console.error('Error parsing date:', e);
                    return '-';
                }
            };

            let content = `
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td style="width: 30%; font-weight: 600;">Route Name:</td>
                                <td>${data.route_name || '<span class="text-muted">-</span>'}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Deskripsi:</td>
                                <td>${data.deskripsi || '<span class="text-muted">-</span>'}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Tanggal Dibuat:</td>
                                <td>${formatDate(data.created_at)}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Terakhir Diupdate:</td>
                                <td>${formatDate(data.updated_at)}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
            
            $('#detailPermissionContent').html(content);
            new bootstrap.Modal(document.getElementById('detailPermissionModal')).show();
        } else {
            showErrorAlert(response.message || 'Gagal memuat detail permission');
        }
    })
    .fail(function(xhr) {
        showErrorAlert(xhr.responseJSON?.message || 'Gagal memuat detail permission');
    });
}

function deletePermission(id) {
    if (!{{ $canDelete ? 'true' : 'false' }}) {
        showErrorAlert('Anda tidak memiliki izin untuk menghapus data.');
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus permission ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            $.ajax({
                url: `{{ url('permissions') }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.close(); // Close loading dialog
                    
                    if (response.success) {
                        // Reload table
                        $('#permissionTable').DataTable().ajax.reload(null, false);
                        
                        // Show success notification
                        showSuccessAlert(response.message || 'Permission berhasil dihapus');
                    } else {
                        showErrorAlert(response.message || 'Gagal menghapus data');
                    }
                },
                error: function(xhr) {
                    Swal.close(); // Close loading dialog
                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan pada server';
                    showErrorAlert(message);
                }
            });
        }
    });
}
</script>
@endpush