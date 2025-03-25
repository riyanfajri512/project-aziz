@extends('layout.app')
@section('title', 'Supplier')

@section('main')
    <!-- Modal -->
    <div class="modal fade" id="modalSupplier" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="modalLabel">Tambah Supplier</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formSupplier">
                        <input type="hidden" id="supplierId">
                        <div class="form-group mb-3">
                            <label for="nama">Nama Supplier</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
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
                <h3>Supplier</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <button id="btnSupplier" class="btn btn-primary">Tambah Supplier</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class= "table" id="supplierTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Supplier</th>
                                            <th>Alamat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($supplier as $jenis)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $jenis->nama }}</td>
                                                <td>{{ $jenis->alamat }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm btnEditSupplier"
                                                        data-id="{{ $jenis->id }}">Edit</button>
                                                    <button class="btn btn-danger btn-sm btnDeleteSupplier"
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
            $('#supplierTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });

            // Tampilkan Modal Tambah
            $("#btnSupplier").click(function () {
                $("#modalLabel").text("Tambah Supplier");
                $("#formSupplier")[0].reset();
                $("#supplierId").val("");
                $("#modalSupplier").modal("show");
            });

            // Tampilkan Modal Edit
            $(".btnEditSupplier").click(function () {
                $("#modalLabel").text("Edit Supplier");
                $("#supplierId").val($(this).data("id"));
                $("#nama").val($(this).closest('tr').find('td:eq(1)').text());
                $("#alamat").val($(this).closest('tr').find('td:eq(2)').text());
                $("#modalSupplier").modal("show");
            });

            // Simpan Data (Tambah/Edit)
            $("#formSupplier").submit(function (e) {
                e.preventDefault();

                let id = $("#supplierId").val();
                let nama = $("#nama").val();
                let alamat = $("#alamat").val();

                let url = id ? `/supplier/update/${id}` : "/supplier/store";
                let type = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    type: type,
                    data: {
                        id: id,
                        nama: nama,
                        alamat: alamat,
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
            $(".btnDeleteSupplier").click(function () {
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
                            url: `/supplier/destroy/${id}`,
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