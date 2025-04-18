@extends('layout.app')
@section('title', 'Tambah Pendistribusian')

@section('main')
    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Tambah Pendistribusian</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            Form Tambah Pendistribusian
                        </div>

                        <div class="card-body">
                            <form id="formPendistribusian">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nomor Perbaikan</label>
                                            <select class="form-control" name="no_perbaikan" id="select-perbaikan" required>
                                                <option value="">Pilih Nomor Perbaikan</option>
                                                <!-- Data akan diisi secara dinamis -->
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">User</label>
                                            <input type="text" class="form-control" id="user-perbaikan" value="{{ Auth::user()->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" name="tanggal" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kode Distribusi</label>
                                            <input type="text" class="form-control" name="kode_distribusi" value="DIST-{{ date('YmdHis') }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mt-3">
                                    <table class="table" id="tabel-sparepart-distribusi">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Kode Sparepart</th>
                                                <th>Jenis Kendaraan</th>
                                                <th>Nama Sparepart</th>
                                                <th class="text-center">Stok Tersedia</th>
                                                <th class="text-center">Qty Distribusi</th>
                                                <th class="text-right">Unit</th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-container-distribusi">
                                            <!-- Baris item akan diisi secara dinamis -->
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Unit Tujuan</label>
                                            <select class="form-control" name="unit_id" required>
                                                <option value="">Pilih Unit Tujuan</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi/Catatan</label>
                                            <textarea class="form-control" name="deskripsi"></textarea>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
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
            // Form submission
            $("#formPendistribusian").submit(function(e) {
                e.preventDefault();

                // Validasi form
                let isValid = true;
                $('input[required]').each(function() {
                    if ($(this).val().trim() === '') {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Harap isi semua field yang wajib!'
                    });
                    return;
                }

                // Kirim data ke server
                Swal.fire({
                    title: 'Menyimpan Data...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Simulasi AJAX request
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data pendistribusian berhasil disimpan',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href =
                        '/pendistribusian'; // Redirect ke halaman pendistribusian
                    });
                }, 1500);
            });

            // Reset form validation on input
            $('input').on('input', function() {
                $(this).removeClass('is-invalid');
            });
        });
    </script>
@endsection
