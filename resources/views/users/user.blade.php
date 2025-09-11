@extends('layouts.app')

@section('title', 'User Management - Kasbon System')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users me-2"></i>Data User
        </h1>
    </div>
    
    <!-- DataTables Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users me-2"></i>Data User
            </h6>
            <div>
                <button class="btn btn-primary" onclick="addUser()">
                    <i class="fas fa-plus me-1"></i>Tambah User
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

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userForm">
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
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">Detail User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">NIP</label>
                            <p class="form-control-plaintext border p-2 bg-light" id="view-nip"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <p class="form-control-plaintext border p-2 bg-light" id="view-nama"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Posisi</label>
                            <p class="form-control-plaintext border p-2 bg-light" id="view-posisi"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">User Group</label>
                            <p class="form-control-plaintext border p-2 bg-light" id="view-user-group"></p>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <p class="form-control-plaintext border p-2 bg-light" id="view-email"></p>
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
let currentUserId = null;

$(document).ready(function() {
    initDataTable();
    initForm();
});

function initDataTable() {
    dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('user.getData') }}",
            type: 'GET'
        },
        columns: [
            { data: 'nip', name: 'nip' },
            { data: 'nama', name: 'nama' },
            { data: 'posisi', name: 'posisi' },
            { data: 'email', name: 'email' },
            { data: 'user_group', name: 'user_group', orderable: false },
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
    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let url = isEdit ? "{{ route('user.update', ':id') }}".replace(':id', currentUserId) : "{{ route('user.store') }}";
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
                    $('#userModal').modal('hide');
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

function addUser() {
    isEdit = false;
    currentUserId = null;
    $('#userModalLabel').text('Tambah User');
    $('#userForm')[0].reset();
    $('#password').prop('required', true);
    $('#password-required').show();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').empty();
    $('#userModal').modal('show');
}

function viewUser(id) {
    $.ajax({
        url: "{{ route('user.show', ':id') }}".replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                isEdit = true;
                currentUserId = id;
                let user = response.data;
                
                $('#userModalLabel').text('Edit User');
                $('#nip').val(user.nip);
                $('#nama').val(user.nama);
                $('#posisi').val(user.posisi);
                $('#user_group_id').val(user.user_group_id).trigger('change');
                $('#email').val(user.email);
                $('#password').prop('required', false);
                $('#password-required').hide();
                
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();
                $('#userModal').modal('show');
            }
        },
        error: function() {
            showAlert('error', 'Error!', 'Tidak dapat memuat data user.');
        }
    });
}

function deleteUser(id) {
    showConfirm(
        'Hapus User?',
        'User yang dihapus tidak dapat dikembalikan!',
        function() {
            $.ajax({
                url: "{{ route('user.destroy', ':id') }}".replace(':id', id),
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
                        showAlert('error', 'Error!', 'Terjadi kesalahan saat menghapus user.');
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