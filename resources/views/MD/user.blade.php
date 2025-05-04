@extends('layout.app')
@section('title', 'User Management')

@section('main')
    <div class="modal fade" id="modalUser" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="modalLabel">Tambah User</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formUser">
                        @csrf
                        <input type="hidden" id="userId" name="id">
                        <div class="form-group mb-3">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan name"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Masukkan password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="atasan">Atasan</option>
                                <option value="karyawan">Karyawan</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>User Management</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Daftar User</h5>
                            <button class="btn btn-primary" id="btnAddUser">
                                <i class="fas fa-plus"></i> Tambah User
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="userTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr class="{{ $user->deleted_at ? 'table-danger' : '' }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $user->role == 'atasan' ? 'primary' : 'success' }}">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($user->deleted_at)
                                                        <span class="badge bg-danger">Deleted</span>
                                                    @else
                                                        <span class="badge bg-success">Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if(!$user->deleted_at)
                                                            <button class="btn btn-sm btn-primary btn-edit"
                                                                data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                                                data-email="{{ $user->email }}" data-role="{{ $user->role }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger btn-delete"
                                                                data-id="{{ $user->id }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-warning btn-reset"
                                                                data-id="{{ $user->id }}">
                                                                <i class="fas fa-key"></i>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-success btn-restore"
                                                                data-id="{{ $user->id }}">
                                                                <i class="fas fa-undo"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger btn-force-delete"
                                                                data-id="{{ $user->id }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')


    <script>
        $('#btnAddUser').on('click', function () {
            $('#modalLabel').text('Tambah User Baru');
            $('#formUser')[0].reset();
            $('#userId').val('');
            $('#modalUser').modal('show');
        });
        // Submit form
        $('#formUser').on('submit', function (e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let userId = $('#userId').val();
            let url = userId ? '/user/update/' + userId : '/user/store';
            let method = userId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 1500
                    }).then(() => {
                        $('#modalUser').modal('hide');
                        location.reload();
                    });
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        let errorMessages = '';
                        $.each(errors, function (key, value) {
                            errorMessages += value + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            html: errorMessages
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON.message || 'Terjadi kesalahan'
                        });
                    }
                }
            });
        });

        // Edit user
        $(document).on('click', '.btn-edit', function () {
            $('#modalLabel').text('Edit User');
            $('#userId').val($(this).data('id'));
            $('#name').val($(this).data('name'));
            $('#email').val($(this).data('email'));
            $('#role').val($(this).data('role'));
            $('#modalUser').modal('show');
        });

        // Delete user
        $(document).on('click', '.btn-delete', function () {
            let userId = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data user akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/user/destroy/' + userId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON.message || 'Terjadi kesalahan'
                            });
                        }
                    });
                }
            });
        });

        // Reset password
        $(document).on('click', '.btn-reset', function () {
            let userId = $(this).data('id');

            Swal.fire({
                title: 'Reset Password',
                text: "Password akan direset ke default (password123)",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/user/' + userId + '/reset-password',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1500
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON.message || 'Terjadi kesalahan'
                            });
                        }
                    });
                }
            });
        });


       
    </script>
@endsection