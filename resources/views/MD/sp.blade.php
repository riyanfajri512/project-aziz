@extends('layout.app')
@section('title', 'SP')

@section('main')
    <!-- Modal -->
    <div class="modal fade" id="modalSP" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="modalLabel">Tambah SP</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formSP">
                        <input type="hidden" id="spId">
                        <div class="form-group mb-3">
                            <label for="no">No</label>
                            <input type="text" class="form-control" id="no" placeholder="Masukan Deskripsi" name="no" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Deskripsi" required>
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
                <h3>SP</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <button id="btnSP" class="btn btn-primary">Tambah SP</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="spTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sp as $jenis)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $jenis->no }}</td>
                                                <td>{{ $jenis->nama }}</td>
                                                <td>
                                                    <button class="btn btn-primary btnEdit" data-id="{{ $jenis->id }}"
                                                        data-no="{{ $jenis->no }}" data-nama="{{ $jenis->nama }}">Edit</button>
                                                    <button class="btn btn-danger btnDelete"
                                                        data-id="{{ $jenis->id }}">Hapus</button>
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
        $(document).ready(function () {
            $('#spTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
            // Tampilkan Modal Tambah
            $("#btnSP").click(function () {
                $("#modalLabel").text("Tambah SP");
                $("#formSP")[0].reset();
                $("#spId").val("");
                $("#modalSP").modal("show");
            });

            // Tampilkan Modal Edit
            $(".btnEdit").click(function () {
                $("#modalLabel").text("Edit SP");
                $("#spId").val($(this).data("id"));
                $("#no").val($(this).data("no")); // Ensure 'no' field is populated
                $("#nama").val($(this).data("nama"));
                $("#modalSP").modal("show");
            });

            // Simpan Data (Tambah/Edit)
            $("#formSP").submit(function (e) {
                e.preventDefault();

                let id = $("#spId").val();
                let no = $("#no").val(); // Correctly retrieve 'no' field value
                let nama = $("#nama").val();

                let url = id ? `/sp/update/${id}` : "/sp/store";
                let type = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    type: type,
                    data: {
                        id: id,
                        no: no,
                        nama: nama,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: "success",
                            title: id ? "Berhasil Diperbarui!" : "Berhasil Ditambahkan!",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: xhr.responseJSON?.message || "Terjadi kesalahan."
                        });
                    }
                });
            });

            // Hapus Data dengan SweetAlert2
            $(".btnDelete").click(function () {
                let id = $(this).data("id");

                Swal.fire({
                    title: "Yakin ingin menghapus?",
                    text: "Data akan dihapus secara permanen!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/sp/destroy/${id}`,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Berhasil Dihapus!",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal!",
                                    text: xhr.responseJSON?.message || "Terjadi kesalahan."
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection