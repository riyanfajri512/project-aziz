@extends('layout.app')
@section('title', 'Permintaan')

@section('main')

    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Tambah Pernerimaan</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            Form Tambah Permintaan
                        </div>

                        <div class="card-body">
                            <form id="formPenerimaan">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Permintaan ID</label>
                                            <input type="text" class="form-control" name="permintaan_Id">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">User_Id</label>
                                            <input type="text" class="form-control" name="cabang">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi</label>
                                            <input type="text" class="form-control" name="Deskripsi">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jumlah</label>
                                            <input type="number" class="form-control" name="Jumlah">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Balance</label>
                                            <input type="text" class="form-control" name="balance">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Harga</label>
                                            <input type="number" class="form-control" name="total" step="0.01"  min="0">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Total</label>
                                            <input type="number" class="form-control" name="harga" step="0.01"  min="0">
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
            $("#formPenerimaan").submit(function(event) {
                event.preventDefault(); // Mencegah reload halaman

                let isValid = true;

                // Loop setiap input dalam form
                $("#formPenerimaan input, #formPenerimaan textarea, #formPenerimaan select").each(
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

                    $("#formPenerimaan")[0].reset(); // Reset form setelah sukses
                }, 2000);
            });

            // Ketika user mulai mengetik, hilangkan pesan error
            $("#formPenerimaan input, #formPenerimaan textarea, #formPenerimaan select").on("input", function() {
                $(this).siblings(".text-danger").addClass("d-none");
            });
        });
    </script>
@endsection
