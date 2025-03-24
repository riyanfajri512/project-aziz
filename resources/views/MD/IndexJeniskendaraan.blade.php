@extends('layout.app')
@section('title', 'Master Data')

@section('main')
    <!-- Modal -->
    <div class="modal fade" id="modalKendaraan" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="modalLabel">Tambah Kendaraan</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formKendaraan">
                        <input type="hidden" id="kendaraanId">

                        <div class="form-group mb-3">
                            <label for="nama">Nama Kendaraan</label>
                            <input type="text" class="form-control" id="nama" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="singkatan">Singkatan</label>
                            <input type="text" class="form-control" id="singkatan" required>
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
                <h3>Jenis Kendaraan</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-primary" id="btnTambahKendaraan">Tambah Kendaraan</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="jenisKendaraanTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama</th>
                                            <th>Singkatan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jenisKendaraan as $jenis)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $jenis->nama }}</td>
                                                <td>{{ $jenis->singkatan }}</td>
                                                <td>
                                                    <button class="btn btn-primary btnEdit" data-id="{{ $jenis->id }}"
                                                        data-nama="{{ $jenis->nama }}"
                                                        data-singkatan="{{ $jenis->singkatan }}">Edit</button>
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
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#jenisKendaraanTable').DataTable({
                "paging": true, // Aktifkan pagination
                "lengthChange": true, // Aktifkan pilihan jumlah data per halaman
                "searching": true, // Aktifkan fitur pencarian
                "ordering": true, // Aktifkan fitur sorting
                "info": true, // Tampilkan info jumlah data
                "autoWidth": false, // Matikan auto width agar tabel lebih responsif
            });



            // Tampilkan Modal Tambah
            $("#btnTambahKendaraan").click(function() {
                $("#modalLabel").text("Tambah Kendaraan");
                $("#formKendaraan")[0].reset();
                $("#kendaraanId").val("");
                $("#modalKendaraan").modal("show");
            });

            // Tampilkan Modal Edit
            $(".btnEdit").click(function() {
                $("#modalLabel").text("Edit Kendaraan");
                $("#kendaraanId").val($(this).data("id"));
                $("#nama").val($(this).data("nama"));
                $("#singkatan").val($(this).data("singkatan"));
                $("#modalKendaraan").modal("show");
            });

            // Simpan Data (Tambah/Edit)
            $("#formKendaraan").submit(function(e) {
                e.preventDefault();

                let id = $("#kendaraanId").val();
                let nama = $("#nama").val();
                let singkatan = $("#singkatan").val();

                let url = id ? `/jeniskendaraan/update/${id}` : "/jeniskendaraan/store";
                let type = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    type: type,
                    data: {
                        nama: nama,
                        singkatan: singkatan,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: "success",
                            title: id ? "Berhasil Diperbarui!" :
                                "Berhasil Ditambahkan!",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });

            // Hapus Data dengan SweetAlert2
            $(".btnDelete").click(function() {
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
                            url: `/jeniskendaraan/destroy/${id}`,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
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
