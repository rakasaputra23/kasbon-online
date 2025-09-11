@extends('layouts.app')

@section('title', 'User Groups - Kasbon System')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-tag me-2"></i>Data User Group
        </h1>
    </div>
    
    <!-- DataTables Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user-tag me-2"></i>Data User Group
            </h6>
            <div>
                <button class="btn btn-primary" onclick="addUserGroup()">
                    <i class="fas fa-plus me-1"></i>Tambah User Group
                </button>
                <button class="btn btn-secondary" onclick="refreshTable()">
                    <i class="fas fa-sync-alt me-1"></i>Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Total Users</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit User Group Modal -->
<div class="modal fade" id="userGroupModal" tabindex="-1" aria-labelledby="userGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userGroupModalLabel">Tambah User Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userGroupForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <div class="form-text">Deskripsi singkat tentang user group ini.</div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Group Modal -->
<div class="modal fade" id="viewUserGroupModal" tabindex="-1" aria-labelledby="viewUserGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserGroupModalLabel">Detail User Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <p class="form-control-plaintext border p-2 bg-light" id="view-name"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Total Users</label>
                            <p class="form-control-plaintext border p-2 bg-light" id="view-users-count"></p>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <p class="form-control-plaintext border p-2 bg-light" id="view-description" style="min-height: 80px;"></p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Dibuat</label>
                            <p class="form-control-plaintext border p-2 bg-light" id="view-created-at"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Terakhir Diupdate</label>
                            <p class="form-control-plaintext border p-2 bg-light" id="view-updated-at"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let dataTable;
let isEdit = false;
let currentUserGroupId = null;

$(document).ready(function() {
    initDataTable();
    initForm();
});

function initDataTable() {
    dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('user.group.getData') }}",
            type: 'GET'
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'total_users', name: 'users_count', orderable: false },
            { data: 'tanggal_dibuat', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        pageLength: 10,
        responsive: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });
}

function initForm() {
    $('#userGroupForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let url = isEdit ? "{{ route('user.group.update', ':id') }}".replace(':id', currentUserGroupId) : "{{ route('user.group.store') }}";
        let method = isEdit ? 'PUT' : 'POST';
        
        if (isEdit) {
            formData.append('_method', 'PUT');
        }
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').empty();
        
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#userGroupModal').modal('hide');
                    dataTable.ajax.reload();
                    showAlert('success', 'Berhasil!', response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key).siblings('.invalid-feedback').text(value[0]);
                    });
                } else {
                    showAlert('error', 'Error!', 'Terjadi kesalahan saat menyimpan data.');
                }
            }
        });
    });
}

function addUserGroup() {
    isEdit = false;
    currentUserGroupId = null;
    $('#userGroupModalLabel').text('Tambah User Group');
    $('#userGroupForm')[0].reset();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    $('#userGroupModal').modal('show');
}

function viewUserGroup(id) {
    $.ajax({
        url: "{{ route('user.group.show', ':id') }}".replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let userGroup = response.data;
                $('#view-name').text(userGroup.name);
                $('#view-description').text(userGroup.description || '-');
                $('#view-users-count').text(userGroup.users_count + ' users');
                $('#view-created-at').text(new Date(userGroup.created_at).toLocaleString('id-ID'));
                $('#view-updated-at').text(new Date(userGroup.updated_at).toLocaleString('id-ID'));
                $('#viewUserGroupModal').modal('show');
            }
        },
        error: function() {
            showAlert('error', 'Error!', 'Tidak dapat memuat data user group.');
        }
    });
}

function editUserGroup(id) {
    $.ajax({
        url: "{{ route('user.group.show', ':id') }}".replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                isEdit = true;
                currentUserGroupId = id;
                let userGroup = response.data;
                
                $('#userGroupModalLabel').text('Edit User Group');
                $('#name').val(userGroup.name);
                $('#description').val(userGroup.description);
                
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();
                $('#userGroupModal').modal('show');
            }
        },
        error: function() {
            showAlert('error', 'Error!', 'Tidak dapat memuat data user group.');
        }
    });
}

function deleteUserGroup(id) {
    showConfirm(
        'Hapus User Group?',
        'User Group yang dihapus tidak dapat dikembalikan! Pastikan tidak ada user yang menggunakan group ini.',
        function() {
            $.ajax({
                url: "{{ route('user.group.destroy', ':id') }}".replace(':id', id),
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        dataTable.ajax.reload();
                        showAlert('success', 'Berhasil!', response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        showAlert('error', 'Error!', xhr.responseJSON.message);
                    } else {
                        showAlert('error', 'Error!', 'Terjadi kesalahan saat menghapus user group.');
                    }
                }
            });
        }
    );
}

function refreshTable() {
    dataTable.ajax.reload();
    showAlert('info', 'Refresh!', 'Data berhasil direfresh.');
}
</script>
@endpush