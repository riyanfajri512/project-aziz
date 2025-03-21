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
    <title>Selamat datang di aplikasi PT. XYZ</title>
    <link href="{{ asset('template/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/auth.css') }}" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <img class="brand" src="assets/img/bootstraper-logo.png" alt="bootstraper logo">
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

    <script>
        $(document).ready(function() {
            $("#loginForm").submit(function(event) {
                event.preventDefault(); // Mencegah form reload

                var email = $("#email").val();
                var password = $("#password").val();

                $.ajax({
                    url: "{{ route('login') }}", // Route ke login
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href =
                            "/dashboard"; // Redirect setelah login berhasil
                        } else {
                            $("#errorMessage").text(response.message); // Tampilkan pesan error
                        }
                    },
                    error: function(xhr) {
                        $("#errorMessage").text(
                        "Email atau password salah."); // Tampilkan error
                    }
                });
            });
        });
    </script>
</body>

</html>
