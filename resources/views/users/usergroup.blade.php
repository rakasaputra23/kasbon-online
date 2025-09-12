@extends('layouts.app')

@section('title', 'User Group Management - Kasbon System')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">User Group Management</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">User Group</li>
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
    $canCreate = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.store');
    $canEdit = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.update');
    $canDelete = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.destroy');
    $canView = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.show');
    $canManagePermissions = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.permissions');
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-users-cog mr-1"></i>
                    Data User Group
                </h3>
                <div class="card-tools">
                    @if($canCreate)
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#userGroupModal">
                        <i class="fas fa-plus"></i> Tambah User Group
                    </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userGroupTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Group</th>
                                <th>Deskripsi</th>
                                <th>Jumlah User</th>
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

<!-- Modal User Group -->
<div class="modal fade" id="userGroupModal" tabindex="-1" aria-labelledby="userGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userGroupModalLabel">Tambah User Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userGroupForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Group <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <div class="form-text">Deskripsi singkat tentang user group ini.</div>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                            @php
                                $permissions = App\Models\Permission::orderBy('deskripsi')->get();
                                $groupedPermissions = $permissions->groupBy(function($permission) {
                                    if (str_contains($permission->route_name, 'user.group')) {
                                        return 'User Group Management';
                                    } elseif (str_contains($permission->route_name, 'user.')) {
                                        return 'User Management';
                                    } elseif (str_contains($permission->route_name, 'permissions.')) {
                                        return 'Permission Management';
                                    } elseif (str_contains($permission->route_name, 'profile')) {
                                        return 'Profile';
                                    } else {
                                        return 'General';
                                    }
                                });
                            @endphp
                            
                            @if(count($permissions) > 0)
                                @foreach($groupedPermissions as $category => $categoryPermissions)
                                    <div class="permission-category mb-3">
                                        <h6 class="text-primary border-bottom pb-2">{{ $category }}</h6>
                                        <div class="row">
                                            @foreach($categoryPermissions as $permission)
                                                <div class="col-md-6 col-lg-4 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]" 
                                                               value="{{ $permission->id }}" id="permission_{{ $permission->id }}">
                                                        <label class="form-check-label small" for="permission_{{ $permission->id }}">
                                                            {{ $permission->deskripsi }}
                                                            @if($permission->route_name)
                                                                <small class="text-muted d-block">({{ $permission->route_name }})</small>
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">Tidak ada permissions yang tersedia</p>
                            @endif
                        </div>
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

<!-- Modal Detail User Group -->
<div class="modal fade" id="detailUserGroupModal" tabindex="-1" aria-labelledby="detailUserGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailUserGroupModalLabel">Detail User Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailUserGroupContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Permissions -->
<div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionsModalLabel">Manage Permissions - <span id="permissionGroupName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="permissionUserGroupId">
                <div id="permissionsContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="updatePermissionsBtn">
                    <i class="fas fa-save"></i> Update Permissions
                </button>
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
        canView: {{ $canView ? 'true' : 'false' }},
        canManagePermissions: {{ $canManagePermissions ? 'true' : 'false' }}
    };

    // Initialize DataTables
    let table = $('#userGroupTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("user.group.getData") }}',
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { data: 'nama' },
            { 
                data: 'description',
                render: function(data) {
                    return data || '-';
                }
            },
            { data: 'users_count' },
            { 
                data: 'created_at',
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
                        buttons += `<button type="button" class="btn btn-sm btn-info" onclick="showDetail(${data})" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>`;
                    }
                    
                    if (permissions.canManagePermissions) {
                        buttons += `<button type="button" class="btn btn-sm btn-secondary" onclick="managePermissions(${data})" title="Permissions">
                            <i class="fas fa-key"></i>
                        </button>`;
                    }
                    
                    if (permissions.canEdit) {
                        buttons += `<button type="button" class="btn btn-sm btn-warning" onclick="editUserGroup(${data})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>`;
                    }
                    
                    if (permissions.canDelete && data != 1) {
                        buttons += `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUserGroup(${data})" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>`;
                    }
                    
                    buttons += '</div>';
                    
                    if (!permissions.canView && !permissions.canEdit && !permissions.canDelete && !permissions.canManagePermissions) {
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
        order: [[0, 'asc']]
    });

    // Form submission
    $('#userGroupForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let url = $('#userGroupForm').data('action') || '{{ route("user.group.store") }}';
        let method = $('#userGroupForm').data('method') || 'POST';
        
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('userGroupModal'));
                    modal.hide();
                    table.ajax.reload();
                    Swal.fire('Berhasil!', response.message, 'success');
                    resetForm();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('.form-control, .form-check-input, .border.rounded').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    
                    $.each(errors, function(key, value) {
                        if (key === 'permissions') {
                            $('.border.rounded').addClass('is-invalid');
                            $('.border.rounded').siblings('.invalid-feedback').text(value[0]);
                        } else {
                            $(`[name="${key}"]`).addClass('is-invalid');
                            $(`[name="${key}"]`).siblings('.invalid-feedback').text(value[0]);
                        }
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

    // Update permissions button
    $('#updatePermissionsBtn').on('click', function() {
        updatePermissions();
    });

    // Reset modal when closed
    $('#userGroupModal').on('hidden.bs.modal', function() {
        resetForm();
    });

    // Clear validation on input change
    $(document).on('change', 'input, select, textarea', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });
});

function resetForm() {
    $('#userGroupForm')[0].reset();
    $('#userGroupModalLabel').text('Tambah User Group');
    $('#userGroupForm').removeData('action').removeData('method');
    $('.form-control, .form-check-input, .border.rounded').removeClass('is-invalid');
    $('.invalid-feedback').text('');
}

function editUserGroup(id) {
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

    $.get(`{{ url('user-group') }}/${id}`, function(data) {
        $('#userGroupModalLabel').text('Edit User Group');
        $('#userGroupForm').data('action', `{{ url('user-group') }}/${id}`).data('method', 'PUT');
        
        $('#nama').val(data.userGroup.nama);
        $('#description').val(data.userGroup.description || '');
        
        // Reset all checkboxes
        $('input[name="permissions[]"]').prop('checked', false);
        
        // Check assigned permissions
        if (data.permissions) {
            data.permissions.forEach(permission => {
                $(`#permission_${permission.id}`).prop('checked', true);
            });
        }
        
        const modal = new bootstrap.Modal(document.getElementById('userGroupModal'));
        modal.show();
    }).fail(function() {
        Swal.fire('Error!', 'Gagal memuat data user group', 'error');
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

    $.get(`{{ url('user-group') }}/${id}`, function(data) {
        let createdAt = '-';
        if (data.userGroup.created_at) {
            try {
                let date = new Date(data.userGroup.created_at);
                createdAt = date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } catch (e) {
                console.error('Error parsing date:', e);
            }
        }

        let permissionsList = '<ul class="list-unstyled">';
        if (data.permissions && data.permissions.length > 0) {
            data.permissions.forEach(permission => {
                permissionsList += `<li><i class="fas fa-check-circle text-success mr-2"></i> ${permission.deskripsi}`;
                if (permission.route_name) {
                    permissionsList += ` <small class="text-muted">(${permission.route_name})</small>`;
                }
                permissionsList += '</li>';
            });
        } else {
            permissionsList += '<li class="text-muted">Tidak ada permissions</li>';
        }
        permissionsList += '</ul>';

        let usersList = '<ul class="list-unstyled">';
        if (data.users && data.users.length > 0) {
            data.users.forEach(user => {
                usersList += `<li><i class="fas fa-user mr-2"></i> ${user.nama}`;
                if (user.nip) {
                    usersList += ` <small class="text-muted">(${user.nip})</small>`;
                }
                usersList += '</li>';
            });
        } else {
            usersList += '<li class="text-muted">Tidak ada user dalam group ini</li>';
        }
        usersList += '</ul>';

        let content = `
            <div class="row">
                <div class="col-12">
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 30%;"><strong>Nama Group:</strong></td>
                            <td>${data.userGroup.nama}</td>
                        </tr>
                        <tr>
                            <td><strong>Deskripsi:</strong></td>
                            <td>${data.userGroup.description || '-'}</td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah User:</strong></td>
                            <td>${data.users ? data.users.length : 0} user</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Dibuat:</strong></td>
                            <td>${createdAt}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <h6><strong>Permissions:</strong></h6>
                    <div class="mb-3">
                        ${permissionsList}
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <h6><strong>Users dalam Group:</strong></h6>
                    <div>
                        ${usersList}
                    </div>
                </div>
            </div>
        `;
        
        $('#detailUserGroupContent').html(content);
        const modal = new bootstrap.Modal(document.getElementById('detailUserGroupModal'));
        modal.show();
    }).fail(function() {
        Swal.fire('Error!', 'Gagal memuat detail user group', 'error');
    });
}

function deleteUserGroup(id) {
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
        text: "Data user group akan dihapus permanen!",
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
                url: `{{ url('user-group') }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#userGroupTable').DataTable().ajax.reload();
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

function managePermissions(id) {
    const permissions = {
        canManagePermissions: {{ $canManagePermissions ? 'true' : 'false' }}
    };
    
    if (!permissions.canManagePermissions) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk mengelola permissions.'
        });
        return;
    }

    $.ajax({
        url: `{{ url('user-group') }}/${id}/permissions`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#permissionUserGroupId').val(id);
                $('#permissionGroupName').text(response.userGroup.nama);
                
                $('#permissionsContainer').empty();
                
                if (response.groupedPermissions) {
                    Object.keys(response.groupedPermissions).forEach(category => {
                        let categoryHtml = `
                            <div class="permission-category mb-4">
                                <h6 class="text-primary border-bottom pb-2">${category}</h6>
                                <div class="row">
                        `;
                        
                        response.groupedPermissions[category].forEach(permission => {
                            let isChecked = response.assignedPermissions.includes(permission.id) ? 'checked' : '';
                            categoryHtml += `
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="permissions[]" value="${permission.id}" 
                                               id="perm_${permission.id}" ${isChecked}>
                                        <label class="form-check-label small" for="perm_${permission.id}">
                                            ${permission.deskripsi}
                                            ${permission.route_name ? `<small class="text-muted d-block">(${permission.route_name})</small>` : ''}
                                        </label>
                                    </div>
                                </div>
                            `;
                        });
                        
                        categoryHtml += '</div></div>';
                        $('#permissionsContainer').append(categoryHtml);
                    });
                } else {
                    let permissionsHtml = '<div class="row">';
                    response.allPermissions.forEach(permission => {
                        let isChecked = response.assignedPermissions.includes(permission.id) ? 'checked' : '';
                        permissionsHtml += `
                            <div class="col-md-6 col-lg-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="permissions[]" value="${permission.id}" 
                                           id="perm_${permission.id}" ${isChecked}>
                                    <label class="form-check-label small" for="perm_${permission.id}">
                                        ${permission.deskripsi}
                                        ${permission.route_name ? `<small class="text-muted d-block">(${permission.route_name})</small>` : ''}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                    permissionsHtml += '</div>';
                    $('#permissionsContainer').html(permissionsHtml);
                }
                
                const modal = new bootstrap.Modal(document.getElementById('permissionsModal'));
                modal.show();
            }
        },
        error: function(xhr) {
            Swal.fire('Error!', 'Gagal memuat data permissions', 'error');
        }
    });
}

function updatePermissions() {
    const permissions = {
        canManagePermissions: {{ $canManagePermissions ? 'true' : 'false' }}
    };
    
    if (!permissions.canManagePermissions) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk mengelola permissions.'
        });
        return;
    }

    let id = $('#permissionUserGroupId').val();
    let selectedPermissions = [];
    
    $('input[name="permissions[]"]:checked').each(function() {
        selectedPermissions.push($(this).val());
    });
    
    const updateBtn = $('#updatePermissionsBtn');
    const originalText = updateBtn.html();
    updateBtn.html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);

    $.ajax({
        url: `{{ url('user-group') }}/${id}`,
        method: 'POST',
        data: {
            nama: $('#permissionGroupName').text(),
            permissions: selectedPermissions,
            _method: 'PUT',
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('permissionsModal'));
                modal.hide();
                Swal.fire('Berhasil!', 'Permissions berhasil diupdate', 'success');
            }
        },
        error: function(xhr) {
            Swal.fire('Error!', xhr.responseJSON?.message || 'Gagal mengupdate permissions', 'error');
        },
        complete: function() {
            updateBtn.html(originalText).prop('disabled', false);
        }
    });
}
</script>
@endpush