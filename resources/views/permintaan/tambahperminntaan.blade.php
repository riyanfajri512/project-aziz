@extends('layout.app')
@section('title', 'Permintaan')

@section('main')

    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Tambah Permintaan</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            Form Tambah Permintaan
                        </div>

                        <div class="card-body">
                            <form id="formPermintaan">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Unit (Pembuat)</label>
                                            <input type="text" class="form-control" name="unit">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Cabang</label>
                                            <input type="text" class="form-control" name="cabang">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Lokasi</label>
                                            <input type="text" class="form-control" name="lokasi">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kode Pemesanan</label>
                                            <input type="text" class="form-control" name="kode_pemesanan">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Lokasi ID</label>
                                            <input type="text" class="form-control" name="lokasi_id">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Dibuat</label>
                                            <input type="date" class="form-control" name="tanggal_dibuat">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi/Catatan</label>
                                            <textarea class="form-control" name="deskripsi"></textarea>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">File</label>
                                            <input type="file" class="form-control" name="file">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Sp ID</label>
                                            <input type="text" class="form-control" name="sp_id">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select class="form-control" name="status">
                                                <option value="">Pilih Status</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Approved">Approved</option>
                                            </select>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Suplier ID</label>
                                            <input type="text" class="form-control" name="suplier_id">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Sparepart ID</label>
                                            <input type="text" class="form-control" name="sparepart_id">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <button type="reset" class="btn btn-secondary ms-2">Batal</button>
                                </div>
                            </form>
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
            $("#formPermintaan").submit(function(event) {
                event.preventDefault(); // Mencegah reload halaman

                let isValid = true;

                // Loop setiap input dalam form
                $("#formPermintaan input, #formPermintaan textarea, #formPermintaan select").each(
            function() {
                    if ($(this).val().trim() === "") {
                        $(this).siblings(".text-danger").removeClass(
                        "d-none"); // Tampilkan pesan error
                        isValid = false;
                    } else {
                        $(this).siblings(".text-danger").addClass(
                        "d-none"); // Sembunyikan pesan error jika sudah diisi
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Harap isi semua field yang wajib!",
                    });
                    return;
                }

                // Jika valid, tampilkan loading
                Swal.fire({
                    title: "Mengirim Permintaan...",
                    html: "Mohon tunggu sebentar.",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Simulasi delay request (contoh: request AJAX ke backend)
                setTimeout(function() {
                    Swal.fire({
                        icon: "success",
                        title: "Permintaan Berhasil Dikirim!",
                        text: "Data telah berhasil disimpan.",
                        timer: 2000,
                        showConfirmButton: false
                    });

                    $("#formPermintaan")[0].reset(); // Reset form setelah sukses
                }, 2000);
            });

            // Ketika user mulai mengetik, hilangkan pesan error
            $("#formPermintaan input, #formPermintaan textarea, #formPermintaan select").on("input", function() {
                $(this).siblings(".text-danger").addClass("d-none");
            });
        });
    </script>
@endsection
