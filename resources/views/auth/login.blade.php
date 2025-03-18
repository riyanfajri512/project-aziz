<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4" style="width: 350px;">
        <div class="card-body text-center">
            <div class="mb-3">
            
            </div>
            <h6 class="mb-4 text-muted">Login to your account</h6>
            <form action="" method="">
                <div class="mb-3 text-start">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <div class="mb-3 text-start">
                    <div class="form-check">
                        <input class="form-check-input" name="remember" type="checkbox" value="" id="check1">
                        <label class="form-check-label" for="check1"> Remember me on this device </label>
                    </div>
                </div>
                <button class="btn btn-primary w-100">Login</button>
            </form>
            <p class="mb-2 text-muted mt-3">Forgot password? <a href="forgot-password.html">Reset</a></p>
            <p class="mb-0 text-muted">Don't have an account yet? <a href="signup.html">Signup</a></p>
        </div>
    </div>
</body>
</html>