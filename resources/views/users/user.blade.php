@extends('layouts.app')

@section('title', 'User Management - Kasbon System')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">User Management</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Home
                </a>
            </li>
            <li class="breadcrumb-item active">User</li>
        </ol>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">

<style>
.user-management {
    font-family: 'Inter', sans-serif;
}

.user-management .btn-primary {
    background: linear-gradient(135deg, #1E40AF, #3B82F6);
    border: none;
    border-radius: 6px;
    font-weight: 600;
}

.user-management .btn-primary:hover {
    background: linear-gradient(135deg, #1E3A8A, #1E40AF);
    transform: translateY(-1px);
}

.user-management .btn-group .btn {
    border-radius: 4px;
    font-size: 0.75rem;
    padding: 0.375rem 0.5rem;
    margin: 0 1px;
}

.user-management .table thead th {
    background: #F9FAFB;
    color: #374151;
    font-weight: 600;
    font-size: 0.875rem;
}

.user-management .table tbody tr:hover {
    background: #F9FAFB;
}

.user-management .select2-container--bootstrap4 .select2-selection--single {
    height: calc(2.25rem + 2px);
    border-radius: 6px;
}

.user-management .form-control:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 0.1rem rgba(59, 130, 246, 0.25);
}

.user-management .dataTables_wrapper .dataTables_paginate .paginate_button.current,
.user-management .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #1E40AF !important;
    border-color: #1E40AF !important;
    color: white !important;
}
</style>
@endpush

@section('content')
@php
    Auth::user()->refreshRelations();
    $canCreate = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.store');
    $canEdit = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.update');
    $canDelete = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.destroy');
    $canView = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.show');
@endphp

<div class="user-management">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users mr-2"></i>Data User
                    </h3>
                    @if($canCreate)
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#userModal">
                        <i class="fas fa-plus mr-1"></i>Tambah User
                    </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Posisi</th>
                                    <th>Email</th>
                                    <th>User Group</th>
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

    <!-- Modal User -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="userForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nip" name="nip" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="posisi" class="form-label">Posisi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="posisi" name="posisi" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_group_id" class="form-label">User Group <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="user_group_id" name="user_group_id" required>
                                        <option value="">Pilih User Group</option>
                                        @foreach($userGroups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger" id="password-required">*</span></label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">Kosongkan jika tidak ingin mengubah password (untuk edit).</div>
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

    <!-- Modal Detail User -->
    <div class="modal fade" id="detailUserModal" tabindex="-1" aria-labelledby="detailUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailUserModalLabel">Detail User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailUserContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    const permissions = {
        canCreate: {{ $canCreate ? 'true' : 'false' }},
        canEdit: {{ $canEdit ? 'true' : 'false' }},
        canDelete: {{ $canDelete ? 'true' : 'false' }},
        canView: {{ $canView ? 'true' : 'false' }}
    };

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Pilih User Group...',
        allowClear: true,
        dropdownParent: $('#userModal')
    });

    // Initialize DataTable
    let table = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { 
            url: '{{ route("user.getData") }}', 
            type: 'GET'
        },
        columns: [
            { data: 'nip', name: 'nip' },
            { data: 'nama', name: 'nama' },
            { data: 'posisi', name: 'posisi' },
            { data: 'email', name: 'email' },
            { data: 'user_group', name: 'user_group', orderable: false },
            { data: 'tanggal_dibuat', name: 'created_at' },
            {
                data: 'id',
                render: function(data, type, row) {
                    let buttons = '<div class="btn-group" role="group">';
                    if (permissions.canView) {
                        buttons += `<button type="button" class="btn btn-sm btn-info" onclick="showDetail(${data})" title="Detail">
                                      <i class="fas fa-eye"></i>
                                   </button>`;
                    }
                    if (permissions.canEdit) {
                        buttons += `<button type="button" class="btn btn-sm btn-warning" onclick="editUser(${data})" title="Edit">
                                      <i class="fas fa-edit"></i>
                                   </button>`;
                    }
                    if (permissions.canDelete) {
                        buttons += `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(${data})" title="Hapus">
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
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        order: [[1, 'asc']]
    });

    // Form submission handler
    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const url = $(this).data('action') || '{{ route("user.store") }}';
        const method = $(this).data('method') || 'POST';
        
        if (method === 'PUT') formData.append('_method', 'PUT');

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
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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

    // Clear validation errors on input
    $(document).on('input change', '.form-control, .select2', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
        
        if ($(this).hasClass('select2')) {
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });

    // Reset form when modal is hidden
    $('#userModal').on('hidden.bs.modal', resetForm);
});

// Utility functions
function clearValidationErrors() {
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    $('.select2-container .select2-selection').removeClass('is-invalid');
}

function closeModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
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
            
            if (field.hasClass('select2')) {
                field.next('.select2-container').find('.select2-selection').addClass('is-invalid');
            }
        });
        showErrorAlert('Mohon periksa kembali data yang dimasukkan');
    } else {
        const message = xhr.responseJSON?.message || 'Terjadi kesalahan pada server';
        showErrorAlert(message);
    }
}

function resetForm() {
    $('#userForm')[0].reset();
    $('#userModalLabel').text('Tambah User');
    $('#userForm').removeData('action').removeData('method');
    $('#password').prop('required', true);
    $('#password-required').show();
    clearValidationErrors();
    $('.select2').val(null).trigger('change');
}

function editUser(id) {
    if (!{{ $canEdit ? 'true' : 'false' }}) {
        showErrorAlert('Anda tidak memiliki izin untuk mengedit data.');
        return;
    }

    $.get(`{{ url('user') }}/${id}`)
    .done(function(data) {
        if (data.success) {
            $('#userModalLabel').text('Edit User');
            $('#userForm').data('action', `{{ url('user') }}/${id}`).data('method', 'PUT');
            
            $('#nip').val(data.data.nip);
            $('#nama').val(data.data.nama);
            $('#posisi').val(data.data.posisi);
            $('#user_group_id').val(data.data.user_group_id).trigger('change');
            $('#email').val(data.data.email);
            $('#password').prop('required', false);
            $('#password-required').hide();
            
            new bootstrap.Modal(document.getElementById('userModal')).show();
        } else {
            showErrorAlert(data.message || 'Gagal memuat data user');
        }
    })
    .fail(function(xhr) {
        showErrorAlert(xhr.responseJSON?.message || 'Gagal memuat data user');
    });
}

function showDetail(id) {
    if (!{{ $canView ? 'true' : 'false' }}) {
        showErrorAlert('Anda tidak memiliki izin untuk melihat detail data.');
        return;
    }

    $.get(`{{ url('user') }}/${id}`)
    .done(function(data) {
        if (data.success) {
            const user = data.data;
            const formatDate = (dateStr) => {
                if (!dateStr) return '-';
                return new Date(dateStr).toLocaleDateString('id-ID', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            };
            
            $('#detailUserContent').html(`
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td style="width: 30%; font-weight: 600;">NIP:</td>
                                <td>${user.nip || '<span class="text-muted">-</span>'}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Nama:</td>
                                <td>${user.nama || '<span class="text-muted">-</span>'}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Posisi:</td>
                                <td>${user.posisi || '<span class="text-muted">-</span>'}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Email:</td>
                                <td>${user.email || '<span class="text-muted">-</span>'}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">User Group:</td>
                                <td>
                                    ${user.user_group ? 
                                        `<span class="badge badge-primary">${user.user_group.name}</span>` : 
                                        '<span class="text-muted">-</span>'
                                    }
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Tanggal Dibuat:</td>
                                <td>${formatDate(user.created_at)}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Terakhir Diupdate:</td>
                                <td>${formatDate(user.updated_at)}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `);
            new bootstrap.Modal(document.getElementById('detailUserModal')).show();
        } else {
            showErrorAlert(data.message || 'Gagal memuat detail user');
        }
    })
    .fail(function(xhr) {
        showErrorAlert(xhr.responseJSON?.message || 'Gagal memuat detail user');
    });
}

function deleteUser(id) {
    if (!{{ $canDelete ? 'true' : 'false' }}) {
        showErrorAlert('Anda tidak memiliki izin untuk menghapus data.');
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus user ini?',
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
                url: `{{ url('user') }}/${id}`,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    Swal.close(); // Close loading dialog
                    
                    if (response.success) {
                        // Reload table
                        $('#userTable').DataTable().ajax.reload(null, false);
                        
                        // Show success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'User berhasil dihapus',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end',
                            background: '#d1fae5',
                            color: '#065f46'
                        });
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