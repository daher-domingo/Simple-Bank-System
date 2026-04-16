<?php
    include 'config.php';
    if (!isset($_SESSION["username"]) || $_SESSION["username"] == "") {
        header("Location: index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>BankCentral Dashboard</title>
    <style>
        body {
            background: #f1f5f9;
        }
        .welcome-card {
            border-radius: 20px;
            background: linear-gradient(135deg, #1d3557, #457b9d);
            color: #fff;
        }
        .card-box {
            border-radius: 15px;
            transition: 0.2s;
        }
        .card-box:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="card welcome-card shadow-lg p-4 mb-4">
        <h2 class="fw-bold">Welcome, <?php echo $_SESSION["username"]; ?> </h2>
        <p class="mb-0">Manage your banking transactions and securely.</p>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-box shadow-sm p-4 text-center">
                <h5 class="fw-bold">Deposits</h5>
                <p class="text-muted">Add money to your account</p>
                <a href="main.php?page=deposit" class="btn btn-primary">Go</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-box shadow-sm p-4 text-center">
                <h5 class="fw-bold">Withdraw</h5>
                <p class="text-muted">Withdraw your funds</p>
                <a href="main.php?page=withdrawal" class="btn btn-danger">Go</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-box shadow-sm p-4 text-center">
                <h5 class="fw-bold">Depositors</h5>
                <p class="text-muted">Manage account holders</p>
                <a href="main.php?page=user_profile" class="btn btn-dark">Go</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>