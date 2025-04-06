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
                                            <input type="text" class="form-control" name="no_perbaikan" 
                                                   placeholder="Contoh: DO01" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">User ID</label>
                                            <input type="text" class="form-control" name="user_id" 
                                                   placeholder="Contoh: 1001" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Unit ID</label>
                                            <input type="text" class="form-control" name="unit_id" 
                                                   placeholder="Contoh: U001" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Sparepart Distribusi ID</label>
                                            <input type="text" class="form-control" name="sparepart_distribusi_id"
                                                   placeholder="Contoh: SPD001" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jumlah</label>
                                            <input type="number" class="form-control" name="jumlah" 
                                                   min="1" value="1" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" name="tanggal" required>
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
                    window.location.href = '/pendistribusian'; // Redirect ke halaman pendistribusian
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