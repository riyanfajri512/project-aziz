@extends('layout.app')
@section('title', 'Lokasi')

@section('main')
    <!-- Modal -->
    <div class="modal fade" id="modalLokasi" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="modalLabel">Tambah Lokasi</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formLokasi">
                        <input type="hidden" id="lokasiId">

                        <div class="form-group mb-3">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="unit">Unit</label>
                            <input type="text" class="form-control" id="unit" name="unit" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="cabang">Cabang</label>
                            <input type="text" class="form-control" id="cabang" name="cabang" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" required>
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
                <h3>Lokasi</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">Lokasi</div>
                        <div class="card-body">
                            <button id="btnTambahLokasi" class="btn btn-primary mb-3">Tambah Lokasi</button>
                            <div class="table-responsive">
                                <table class="table" id="lokasiTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Unit</th>
                                            <th>Cabang</th>
                                            <th>Alamat</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lokasi as $jenis)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $jenis->nama }}</td>
                                                <td>{{ $jenis->unit }}</td>
                                                <td>{{ $jenis->cabang }}</td>
                                                <td>{{ $jenis->alamat }}</td>
                                                <td>
                                                    <button class="btn btn-primary btnEdit" data-id="{{ $jenis->id }}"
                                                        data-nama="{{ $jenis->nama }}"
                                                        data-unit="{{ $jenis->unit }}"
                                                        data-cabang="{{ $jenis->cabang }}"
                                                        data-alamat="{{ $jenis->alamat }}">Edit</button>
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
            // Initialize DataTable
            $('#lokasiTable').DataTable();

            // Show Modal for Adding Data
            $("#btnTambahLokasi").click(function () {
                $("#modalLabel").text("Tambah Lokasi");
                $("#formLokasi")[0].reset();
                $("#lokasiId").val("");
                $("#modalLokasi").modal("show");
            });

            // Show Modal for Editing Data
            $(".btnEdit").click(function () {
                $("#modalLabel").text("Edit Lokasi");
                $("#lokasiId").val($(this).data("id"));
                $("#nama").val($(this).data("nama"));
                $("#unit").val($(this).data("unit"));
                $("#cabang").val($(this).data("cabang"));
                $("#alamat").val($(this).data("alamat"));
                $("#modalLokasi").modal("show");
            });

            // Save Data (Add/Edit)
            $("#formLokasi").submit(function (e) { 
                e.preventDefault();

                let id = $("#lokasiId").val();
                let formData = {
                    nama: $("#nama").val(),
                    unit: $("#unit").val(),
                    cabang: $("#cabang").val(),
                    alamat: $("#alamat").val(),
                    _token: "{{ csrf_token() }}"
                };

                let url = id ? `/lokasi/update/${id}` : "/lokasi/store";
                let type = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    type: type,
                    data: formData,
                    success: function (response) {
                        Swal.fire({
                            icon: "success",
                            title: id ? "Berhasil Diperbarui!" : "Berhasil Ditambahkan!",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });

            // Delete Data
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
                            url: `/lokasi/destroy/${id}`,
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
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection