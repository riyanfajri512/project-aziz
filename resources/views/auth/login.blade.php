<!doctype html>
<!--
* Bootstrap Simple Admin Template
* Version: 2.1
* Author: Alexis Luna
* Website: https://github.com/alexis-luna/bootstrap-simple-admin-template
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Selamat datang di aplikasi PT. XYZ</title>
    <link href="{{ asset('template/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/auth.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <img src="{{ asset('template/assets/img/logo_indomaret.png') }}"
                            style="width: 150px; height: auto;" alt="bootraper logo" class="app-logo">
                    </div>
                    <h6 class="mb-4 text-muted">Login to your account</h6>
                    <form id="loginForm">
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label">Username/Email</label>
                            <input type="text" class="form-control" placeholder="Enter your email or username"
                                required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                        {{-- <div class="mb-3 text-start">
                            <div class="form-check">
                                <input class="form-check-input" name="remember" type="checkbox" value=""
                                    id="check1">
                                <label class="form-check-label" for="check1">
                                    Remember me on this device
                                </label>
                            </div>
                        </div> --}}
                        <button type="submit" class="btn btn-primary mb-4" style="width:100%">Login</button>
                    </form>
                    {{-- <p class="mb-2 text-muted">Forgot password? <a href="forgot-password.html">Reset</a></p>
                    <p class="mb-0 text-muted">Don't have account yet? <a href="signup.html">Signup</a></p> --}}
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('template/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#loginForm").submit(function(event) {
                event.preventDefault(); // Mencegah reload halaman

                var formData = {
                    email: $("input[placeholder='Enter your email or username']").val(),
                    password: $("input[placeholder='Enter your password']").val(),
                    _token: $('meta[name="csrf-token"]').attr("content")
                };

                // Tampilkan loading saat proses login berjalan
                Swal.fire({
                    title: "Memproses...",
                    text: "Mohon tunggu sebentar.",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "/login",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        console.log("Response dari server:", response); // Debugging

                        Swal.close(); // Tutup loading

                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Login Berhasil",
                                text: "Anda akan dialihkan...",
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Login Gagal",
                                text: response.message ||
                                    "Terjadi kesalahan, coba lagi."
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close(); // Tutup loading jika terjadi error
                        console.log("Error AJAX:", xhr.responseText); // Debugging error
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: "Silakan coba lagi."
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
