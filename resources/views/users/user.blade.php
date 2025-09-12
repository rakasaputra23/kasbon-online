@extends('layouts.app')

@section('title', 'User Management - Kasbon System')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">User Management</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">User</li>
        </ol>
    </div>
</div>
@endsection

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
@endpush

@section('content')
@php
    // Check permissions
    Auth::user()->refreshRelations();
    $canCreate = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.store');
    $canEdit = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.update');
    $canDelete = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.destroy');
    $canView = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.show');
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    Data User
                </h3>
                <div class="card-tools">
                    @if($canCreate)
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#userModal">
                        <i class="fas fa-plus"></i> Tambah User
                    </button>
                    @endif
                </div>
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
                        <i class="fas fa-save"></i> Simpan
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
            <div class="modal-body" id="detailUserContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Permission flags for JavaScript
    const permissions = {
        canCreate: {{ $canCreate ? 'true' : 'false' }},
        canEdit: {{ $canEdit ? 'true' : 'false' }},
        canDelete: {{ $canDelete ? 'true' : 'false' }},
        canView: {{ $canView ? 'true' : 'false' }}
    };

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Initialize DataTables
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
                    
                    if (!permissions.canView && !permissions.canEdit && !permissions.canDelete) {
                        return '-';
                    }
                    
                    return buttons;
                },
                orderable: false,
                searchable: false
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        },
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        order: [[1, 'asc']]
    });

    // Form submission
    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let url = $('#userForm').data('action') || '{{ route("user.store") }}';
        let method = $('#userForm').data('method') || 'POST';
        
        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
                    modal.hide();
                    table.ajax.reload();
                    Swal.fire('Berhasil!', response.message, 'success');
                    resetForm();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('.form-control, .form-select').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    
                    $.each(errors, function(key, value) {
                        $(`[name="${key}"]`).addClass('is-invalid');
                        $(`[name="${key}"]`).siblings('.invalid-feedback').text(value[0]);
                    });
                } else {
                    Swal.fire('Error!', 'Terjadi kesalahan pada server', 'error');
                }
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Reset modal when closed
    $('#userModal').on('hidden.bs.modal', function() {
        resetForm();
    });

    // Clear validation on input change
    $(document).on('change', 'input, select, textarea', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });
});

function resetForm() {
    $('#userForm')[0].reset();
    $('#userModalLabel').text('Tambah User');
    $('#userForm').removeData('action').removeData('method');
    $('#password').prop('required', true);
    $('#password-required').show();
    $('.form-control, .form-select').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    $('.select2').trigger('change');
}

function editUser(id) {
    const permissions = {
        canEdit: {{ $canEdit ? 'true' : 'false' }}
    };
    
    if (!permissions.canEdit) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk mengedit data.'
        });
        return;
    }

    $.get(`{{ url('user') }}/${id}`, function(data) {
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
            
            const modal = new bootstrap.Modal(document.getElementById('userModal'));
            modal.show();
        }
    }).fail(function() {
        Swal.fire('Error!', 'Gagal memuat data user', 'error');
    });
}

function showDetail(id) {
    const permissions = {
        canView: {{ $canView ? 'true' : 'false' }}
    };
    
    if (!permissions.canView) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk melihat detail data.'
        });
        return;
    }

    $.get(`{{ url('user') }}/${id}`, function(data) {
        if (data.success) {
            let user = data.data;
            
            let createdAt = '-';
            let updatedAt = '-';
            
            if (user.created_at) {
                try {
                    let date = new Date(user.created_at);
                    createdAt = date.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                } catch (e) {
                    console.error('Error parsing created_at date:', e);
                }
            }

            if (user.updated_at) {
                try {
                    let date = new Date(user.updated_at);
                    updatedAt = date.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                } catch (e) {
                    console.error('Error parsing updated_at date:', e);
                }
            }

            let content = `
                <div class="row">
                    <div class="col-12">
                        <table class="table table-borderless">
                            <tr>
                                <td style="width: 30%;"><strong>NIP:</strong></td>
                                <td>${user.nip || '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>${user.nama || '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>Posisi:</strong></td>
                                <td>${user.posisi || '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>${user.email || '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>User Group:</strong></td>
                                <td>${user.user_group ? user.user_group.name : '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Dibuat:</strong></td>
                                <td>${createdAt}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Diupdate:</strong></td>
                                <td>${updatedAt}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            `;
            
            $('#detailUserContent').html(content);
            const modal = new bootstrap.Modal(document.getElementById('detailUserModal'));
            modal.show();
        }
    }).fail(function() {
        Swal.fire('Error!', 'Gagal memuat detail user', 'error');
    });
}

function deleteUser(id) {
    const permissions = {
        canDelete: {{ $canDelete ? 'true' : 'false' }}
    };
    
    if (!permissions.canDelete) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk menghapus data.'
        });
        return;
    }

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data user akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `{{ url('user') }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#userTable').DataTable().ajax.reload();
                        Swal.fire('Berhasil!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message || 'Terjadi kesalahan pada server';
                    Swal.fire('Error!', message, 'error');
                }
            });
        }
    });
}
</script>
@endpush