<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Bank Central Login</title>
</head>

<body style="background: linear-gradient(135deg, #0d1b2a, #1b263b); height:100vh;" class="d-flex justify-content-center align-items-center min-vh-100">
<?php
    include 'config.php';
    session_start();
    $error = 0;
    if (isset($_POST['cmd_login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $que = "Select * from tbl_user where username = '$username' AND password = '$password' limit 1";
        $myresult = mysqli_query($conn, $que);

        if (mysqli_num_rows($myresult) > 0) {
            $record = mysqli_fetch_array($myresult);
            $_SESSION['username'] = $record["username"];
            header("location:main.php");
        } else {
            $error = 1;
        }
        mysqli_close($conn);
    }
?>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="row g-0">
                        <!-- LEFT SIDE (BANK INFO PANEL) -->
                        <div class="col-md-6 text-white d-flex flex-column justify-content-center p-5" style="background: linear-gradient(135deg, #1d3557, #457b9d);">
                            <h1 class="fw-bold mb-3"><i class="fa-solid fa-building-columns"></i> Bank Central</h1>
                            <h5 class="mb-4">Your Trusted Digital Bank</h5>
                            <p class="small">
                                Manage your finances securely. Access your account anytime, anywhere with our reliable banking system.
                            </p>
                            <hr class="bg-light">
                            <small>✔ Secure Login</small><br>
                            <small>✔ Fast Transactions</small><br>
                            <small>✔ 24/7 Access</small>
                        </div>
                        <div class="col-md-6 bg-white p-5">
                            <h3 class="text-center mb-4 fw-bold">Account Login</h3>
                            <div class="mb-3">
                                <label class="form-label">Username / Email</label>
                                <input type="text" class="form-control form-control-lg" name="username" placeholder="Enter your username" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control form-control-lg" name="password" placeholder="Enter your password" required>
                            </div>
                            <div class="d-grid mb-3">
                                <button class="btn btn-primary btn-lg fw-bold" type="submit" name="cmd_login">
                                    Sign In
                                </button>
                            </div>
                            <?php
                                if ($error == 1) {
                            ?>
                            <div class="alert alert-danger text-center">
                                Login Failed! Incorrect username or password.
                            </div>
                            <?php } ?>
                            <div class="text-center mt-3">
                                <small class="text-muted">Secure Banking System © 2026</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>