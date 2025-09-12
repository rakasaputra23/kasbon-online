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
@endpush

@section('content')
@php
    Auth::user()->refreshRelations();
    $canCreate = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('permissions.store');
    $canEdit = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('permissions.update');
    $canDelete = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('permissions.destroy');
    $canView = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('permissions.show');
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-key mr-1"></i>
                    Data Permissions
                </h3>
                <div class="card-tools">
                    @if($canCreate)
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#permissionModal">
                        <i class="fas fa-plus"></i> Tambah Permission
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
                        <i class="fas fa-save"></i> Simpan
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
            { data: 'action', name: 'action', orderable: false, searchable: false }
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('permissionModal'));
                    modal.hide();
                    table.ajax.reload();
                    Swal.fire('Berhasil!', response.message, 'success');
                    resetForm();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('.form-control').removeClass('is-invalid');
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
    $('#permissionModal').on('hidden.bs.modal', function() {
        resetForm();
    });

    $(document).on('change', 'input', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });
});

function resetForm() {
    $('#permissionForm')[0].reset();
    $('#permissionModalLabel').text('Tambah Permission');
    $('#permissionForm').removeData('action').removeData('method');
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
}

function editPermission(id) {
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

    $.get(`{{ url('permissions') }}/${id}`, function(response) {
        if (response.success) {
            let data = response.data;
            $('#permissionModalLabel').text('Edit Permission');
            $('#permissionForm').data('action', `{{ url('permissions') }}/${id}`).data('method', 'PUT');
            
            $('#route_name').val(data.route_name);
            $('#deskripsi').val(data.deskripsi);
            
            const modal = new bootstrap.Modal(document.getElementById('permissionModal'));
            modal.show();
        }
    }).fail(function() {
        Swal.fire('Error!', 'Gagal memuat data permission', 'error');
    });
}

function viewPermission(id) {
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

    $.get(`{{ url('permissions') }}/${id}`, function(response) {
        if (response.success) {
            let data = response.data;
            let createdAt = '-';
            let updatedAt = '-';
            
            if (data.created_at) {
                try {
                    let date = new Date(data.created_at);
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

            if (data.updated_at) {
                try {
                    let date = new Date(data.updated_at);
                    updatedAt = date.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                } catch (e) {
                    console.error('Error parsing date:', e);
                }
            }

            let content = `
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 30%;"><strong>Route Name:</strong></td>
                        <td>${data.route_name}</td>
                    </tr>
                    <tr>
                        <td><strong>Deskripsi:</strong></td>
                        <td>${data.deskripsi}</td>
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
            `;
            
            $('#detailPermissionContent').html(content);
            const modal = new bootstrap.Modal(document.getElementById('detailPermissionModal'));
            modal.show();
        }
    }).fail(function() {
        Swal.fire('Error!', 'Gagal memuat detail permission', 'error');
    });
}

function deletePermission(id) {
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
        text: "Data permission akan dihapus permanen!",
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
                url: `{{ url('permissions') }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#permissionTable').DataTable().ajax.reload();
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