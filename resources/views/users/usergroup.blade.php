    @extends('layouts.app')

    @section('title', 'User Group Management - Kasbon System')

    @section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">User Group Management</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item active">User Group</li>
            </ol>
        </div>
    </div>
    @endsection

    @push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">

    <style>
    .user-group-management {
        font-family: 'Inter', sans-serif;
    }

    .user-group-management .btn-primary {
        background: linear-gradient(135deg, #1E40AF, #3B82F6);
        border: none;
        border-radius: 6px;
        font-weight: 600;
    }

    .user-group-management .btn-primary:hover {
        background: linear-gradient(135deg, #1E3A8A, #1E40AF);
        transform: translateY(-1px);
    }

    .user-group-management .btn-group .btn {
        border-radius: 4px;
        font-size: 0.75rem;
        padding: 0.375rem 0.5rem;
        margin: 0 1px;
    }

    .user-group-management .table thead th {
        background: #F9FAFB;
        color: #374151;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .user-group-management .table tbody tr:hover {
        background: #F9FAFB;
    }

    .user-group-management .form-control:focus {
        border-color: #3B82F6;
        box-shadow: 0 0 0 0.1rem rgba(59, 130, 246, 0.25);
    }

    .user-group-management .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .user-group-management .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #1E40AF !important;
        border-color: #1E40AF !important;
        color: white !important;
    }

    .user-group-management .modal-content {
        border-radius: 8px;
        border: none;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .user-group-management .modal-header {
        background: #F8FAFC;
        border-bottom: 1px solid #E2E8F0;
        border-radius: 8px 8px 0 0;
    }

    .user-group-management .modal-title {
        font-weight: 600;
        color: #1E293B;
    }

    .user-group-management .permission-category {
        border: 1px solid #E5E7EB;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        background: #F9FAFB;
    }
    </style>
    @endpush

    @section('content')
    @php
        Auth::user()->refreshRelations();
        $canCreate = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.store');
        $canEdit = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.update');
        $canDelete = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.destroy');
        $canView = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.show');
        $canManagePermissions = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.permissions');
    @endphp

    <div class="user-group-management">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-users-cog mr-2"></i>Data User Group
                        </h3>
                        @if($canCreate)
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#userGroupModal">
                            <i class="fas fa-plus mr-1"></i>Tambah User Group
                        </button>
                        @endif
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
                                <label for="name" class="form-label">Nama Group <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
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
                                <i class="fas fa-save mr-1"></i>Simpan
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
                    <div class="modal-body" id="detailUserGroupContent"></div>
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
                            <i class="fas fa-save mr-1"></i>Update Permissions
                        </button>
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
            canView: {{ $canView ? 'true' : 'false' }},
            canManagePermissions: {{ $canManagePermissions ? 'true' : 'false' }}
        };

        // Initialize DataTable
        let table = $('#userGroupTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: { 
                url: '{{ route("user.group.getData") }}', 
                type: 'GET'
            },
            columns: [
                { data: 'name', name: 'name' },
                { 
                    data: 'description', 
                    name: 'description',
                    render: function(data) {
                        return data || '-';
                    }
                },
                { data: 'users_count', name: 'users_count', orderable: false },
                { data: 'tanggal_dibuat', name: 'created_at' },
                {
                    data: 'action',
                    render: function(data, type, row) {
                        let buttons = '<div class="btn-group" role="group">';
                        if (permissions.canView) {
                            buttons += `<button type="button" class="btn btn-sm btn-info" onclick="viewUserGroup(${row.id})" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>`;
                        }
                        if (permissions.canManagePermissions) {
                            buttons += `<button type="button" class="btn btn-sm btn-secondary" onclick="managePermissions(${row.id})" title="Permissions">
                                        <i class="fas fa-key"></i>
                                    </button>`;
                        }
                        if (permissions.canEdit) {
                            buttons += `<button type="button" class="btn btn-sm btn-warning" onclick="editUserGroup(${row.id})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>`;
                        }
                        if (permissions.canDelete && row.id != 1) {
                            buttons += `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUserGroup(${row.id})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>`;
                        }
                        buttons += '</div>';
                        return (!permissions.canView && !permissions.canEdit && !permissions.canDelete && !permissions.canManagePermissions) ? 
                            '<span class="text-muted">-</span>' : buttons;
                    },
                    orderable: false,
                    searchable: false,
                    width: '180px'
                }
            ],
            language: { 
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            },
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            order: [[0, 'asc']]
        });

        // Form submission handler
        $('#userGroupForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = $(this).data('action') || '{{ route("user.group.store") }}';
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
        $(document).on('input change', '.form-control, .form-check-input, textarea', function() {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('');
            $('.border.rounded').removeClass('is-invalid');
        });

        // Reset form when modal is hidden
        $('#userGroupModal').on('hidden.bs.modal', resetForm);

        // Update permissions button
        $('#updatePermissionsBtn').on('click', function() {
            updatePermissions();
        });
    });

    // Utility functions
    function clearValidationErrors() {
        $('.form-control, .form-check-input, textarea').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('.border.rounded').removeClass('is-invalid');
    }

    function closeModal() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('userGroupModal'));
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
                if (key === 'permissions') {
                    $('.border.rounded').addClass('is-invalid');
                    $('.border.rounded').siblings('.invalid-feedback').text(value[0]);
                } else {
                    const field = $(`[name="${key}"]`);
                    field.addClass('is-invalid');
                    field.siblings('.invalid-feedback').text(value[0]);
                }
            });
            showErrorAlert('Mohon periksa kembali data yang dimasukkan');
        } else {
            const message = xhr.responseJSON?.message || 'Terjadi kesalahan pada server';
            showErrorAlert(message);
        }
    }

    function resetForm() {
        $('#userGroupForm')[0].reset();
        $('#userGroupModalLabel').text('Tambah User Group');
        $('#userGroupForm').removeData('action').removeData('method');
        clearValidationErrors();
    }

    function editUserGroup(id) {
        if (!{{ $canEdit ? 'true' : 'false' }}) {
            showErrorAlert('Anda tidak memiliki izin untuk mengedit data.');
            return;
        }

        $.get(`{{ url('user-group') }}/${id}`)
        .done(function(data) {
            if (data.success) {
                $('#userGroupModalLabel').text('Edit User Group');
                $('#userGroupForm').data('action', `{{ url('user-group') }}/${id}`).data('method', 'PUT');
                
                $('#name').val(data.data.name);
                $('#description').val(data.data.description || '');
                
                // Reset all checkboxes
                $('input[name="permissions[]"]').prop('checked', false);
                
                // Check assigned permissions
                if (data.data.permissions) {
                    data.data.permissions.forEach(permission => {
                        $(`#permission_${permission.id}`).prop('checked', true);
                    });
                }
                
                new bootstrap.Modal(document.getElementById('userGroupModal')).show();
            } else {
                showErrorAlert(data.message || 'Gagal memuat data user group');
            }
        })
        .fail(function(xhr) {
            showErrorAlert(xhr.responseJSON?.message || 'Gagal memuat data user group');
        });
    }

    function viewUserGroup(id) {
        if (!{{ $canView ? 'true' : 'false' }}) {
            showErrorAlert('Anda tidak memiliki izin untuk melihat detail data.');
            return;
        }

        $.get(`{{ url('user-group') }}/${id}`)
        .done(function(data) {
            if (data.success) {
                const userGroup = data.data;
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
                        return '-';
                    }
                };

                let permissionsList = '<div class="row">';
                if (data.data.permissions && data.data.permissions.length > 0) {
                    data.data.permissions.forEach(permission => {
                        permissionsList += `
                            <div class="col-md-6 col-lg-4 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success mr-2"></i>
                                    <div class="flex-grow-1">
                                        <small class="d-block">${permission.deskripsi}</small>
                                        ${permission.route_name ? `<small class="text-muted">(${permission.route_name})</small>` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    permissionsList += '<div class="col-12"><span class="text-muted">Tidak ada permissions</span></div>';
                }
                permissionsList += '</div>';

                let usersList = '<div class="row">';
                if (data.data.users && data.data.users.length > 0) {
                    data.data.users.forEach(user => {
                        usersList += `
                            <div class="col-md-6 col-lg-4 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user mr-2 text-primary"></i>
                                    <div class="flex-grow-1">
                                        <small class="d-block font-weight-bold">${user.nama}</small>
                                        ${user.nip ? `<small class="text-muted">(${user.nip})</small>` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    usersList += '<div class="col-12"><span class="text-muted">Tidak ada user dalam group ini</span></div>';
                }
                usersList += '</div>';

                $('#detailUserGroupContent').html(`
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td style="width: 30%; font-weight: 600;">Nama Group:</td>
                                    <td>${userGroup.name || '<span class="text-muted">-</span>'}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 600;">Deskripsi:</td>
                                    <td>${userGroup.description || '<span class="text-muted">-</span>'}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 600;">Jumlah User:</td>
                                    <td>${data.data.users ? data.data.users.length : 0} user</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 600;">Tanggal Dibuat:</td>
                                    <td>${formatDate(userGroup.created_at)}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 600;">Terakhir Diupdate:</td>
                                    <td>${formatDate(userGroup.updated_at)}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="mb-4">
                        <h6 class="mb-3"><strong>Permissions:</strong></h6>
                        ${permissionsList}
                    </div>
                    <hr>
                    <div>
                        <h6 class="mb-3"><strong>Users dalam Group:</strong></h6>
                        ${usersList}
                    </div>
                `);
                
                new bootstrap.Modal(document.getElementById('detailUserGroupModal')).show();
            } else {
                showErrorAlert(data.message || 'Gagal memuat detail user group');
            }
        })
        .fail(function(xhr) {
            showErrorAlert(xhr.responseJSON?.message || 'Gagal memuat detail user group');
        });
    }

    function deleteUserGroup(id) {
        if (!{{ $canDelete ? 'true' : 'false' }}) {
            showErrorAlert('Anda tidak memiliki izin untuk menghapus data.');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus user group ini?',
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
                    url: `{{ url('user-group') }}/${id}`,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        Swal.close();
                        
                        if (response.success) {
                            table.ajax.reload(null, false);
                            showSuccessAlert(response.message || 'User group berhasil dihapus');
                        } else {
                            showErrorAlert(response.message || 'Gagal menghapus data');
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        const message = xhr.responseJSON?.message || 'Terjadi kesalahan pada server';
                        showErrorAlert(message);
                    }
                });
            }
        });
    }

    function managePermissions(id) {
        if (!{{ $canManagePermissions ? 'true' : 'false' }}) {
            showErrorAlert('Anda tidak memiliki izin untuk mengelola permissions.');
            return;
        }

        $.ajax({
            url: `{{ url('user-group') }}/${id}/permissions`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#permissionUserGroupId').val(id);
                    $('#permissionGroupName').text(response.userGroup.name);
                    
                    $('#permissionsContainer').empty();
                    
                    if (response.groupedPermissions) {
                        Object.keys(response.groupedPermissions).forEach(category => {
                            let categoryHtml = `
                                <div class="permission-category mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-folder-open mr-2"></i>${category}
                                    </h6>
                                    <div class="row">
                            `;
                            
                            response.groupedPermissions[category].forEach(permission => {
                                let isChecked = response.assignedPermissions.includes(permission.id) ? 'checked' : '';
                                categoryHtml += `
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                name="permissions[]" value="${permission.id}" 
                                                id="perm_${permission.id}" ${isChecked}>
                                            <label class="form-check-label" for="perm_${permission.id}">
                                                <span class="d-block font-weight-medium">${permission.deskripsi}</span>
                                                ${permission.route_name ? `<small class="text-muted">(${permission.route_name})</small>` : ''}
                                            </label>
                                        </div>
                                    </div>
                                `;
                            });
                            
                            categoryHtml += '</div></div>';
                            $('#permissionsContainer').append(categoryHtml);
                        });
                    }
                    
                    new bootstrap.Modal(document.getElementById('permissionsModal')).show();
                } else {
                    showErrorAlert(response.message || 'Gagal memuat data permissions');
                }
            },
            error: function(xhr) {
                showErrorAlert(xhr.responseJSON?.message || 'Gagal memuat data permissions');
            }
        });
    }

    function updatePermissions() {
        if (!{{ $canManagePermissions ? 'true' : 'false' }}) {
            showErrorAlert('Anda tidak memiliki izin untuk mengelola permissions.');
            return;
        }

        const id = $('#permissionUserGroupId').val();
        const selectedPermissions = [];
        
        $('input[name="permissions[]"]:checked').each(function() {
            selectedPermissions.push($(this).val());
        });
        
        const updateBtn = $('#updatePermissionsBtn');
        const originalText = updateBtn.html();
        updateBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Updating...').prop('disabled', true);

        $.ajax({
            url: `{{ url('user-group') }}/${id}`,
            method: 'POST',
            data: {
                name: $('#permissionGroupName').text(),
                permissions: selectedPermissions,
                _method: 'PUT',
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('permissionsModal'));
                    if (modal) modal.hide();
                    showSuccessAlert(response.message || 'Permissions berhasil diupdate');
                    table.ajax.reload(null, false);
                } else {
                    showErrorAlert(response.message || 'Gagal mengupdate permissions');
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Gagal mengupdate permissions';
                showErrorAlert(message);
            },
            complete: function() {
                updateBtn.html(originalText).prop('disabled', false);
            }
        });
    }
    </script>
    @endpush