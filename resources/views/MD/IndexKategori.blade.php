@extends('layout.app')
@section('title', 'Kategori')

@section('main')
    <!-- Modal -->
    <div class="modal fade" id="modalKategori" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="modalLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formKategori">
                        <input type="hidden" id="kategoriId">
                        <div class="form-group mb-3">
                            <label for="kode">Kode</label>
                            <input type="text" class="form-control" id="kode" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kategori">Nama Kategori</label>
                            <input type="text" class="form-control" id="kategori" required>
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
                <h3>Kategori</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-primary" id="btnKategori">Tambah Kategori</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="kategoriTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kategori as $jenis)
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
            $('#kategoriTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
            // Tampilkan Modal Tambah
            $("#btnKategori").click(function () {
                $("#modalLabel").text("Tambah Kategori");
                $("#formKategori")[0].reset();
                $("#kategoriId").val("");
                $("#modalKategori").modal("show");
            });

            // Tampilkan Modal Edit
            $(".btnEdit").click(function () {
                $("#modalLabel").text("Edit Kategori");
                $("#kategoriId").val($(this).data("id"));
                $("#kode").val($(this).data("kode"));
                $("#nama").val($(this).data("nama"));
                $("#modalKategori").modal("show");
            });

            // Simpan Data (Tambah/Edit)
            $("#formKategori").submit(function (e) {
                e.preventDefault();

                let id = $("#kategoriId").val();
                let kode = $("#kode").val(); // Pastikan kode diambil dari input
                let kategori = $("#kategori").val();

                let url = id ? `/kategori/update/${id}` : "/kategori/store";
                let type = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    type: type,
                    data: {
                        no: kode,
                        nama: kategori,
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
                            text: xhr.responseJSON.message || "Terjadi kesalahan.",
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
                            url: `/kategori/destroy/${id}`,
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
                                    text: xhr.responseJSON.message || "Terjadi kesalahan.",
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection