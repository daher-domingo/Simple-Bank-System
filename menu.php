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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>MYBANK</title>
    <style>
        body {
            background: #f1f5f9;
        }
        .navbar {
            background: linear-gradient(135deg, #0d1b2a, #1b263b);
            padding: 14px 20px !important;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
        }
        .navbar-brand {
            font-size: 28px !important;
            font-weight: bold;
            color: #fff !important;
        }
        .nav-link {
            font-size: 18px !important;
            color: #cbd5e1 !important;
            transition: 0.2s;
        }
        .nav-link:hover {
            color: #fff !important;
        }
        .dropdown-menu {
            border-radius: 10px;
        }
        .dropdown-item:hover {
            background-color: #e2e8f0;
        }
        .content {
            padding: 30px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fa-solid fa-building-columns"></i> BankCentral</a>
            <!-- FIXED toggler -->
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- LEFT MENU -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="main.php?page=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="main.php?page=user_profile">Depositors</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Transactions
                        </a>
                        <ul class="dropdown-menu shadow">
                            <li><a class="dropdown-item" href="main.php?page=deposit">Deposit</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="main.php?page=withdrawal">Withdraw</a></li>
                        </ul>
                    </li>

                </ul>
                <!-- RIGHT SIDE (FIXED) -->
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <span class="text-white d-flex align-items-center gap-2">
                        <i class="fas fa-user-circle"></i>
                        <?php echo $_SESSION["username"]; ?>
                    </span>
                    <a class="btn btn-logout btn-outline-light btn-md" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <!-- LOGOUT CONFIRMATION MODAL -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <a href="logout.php" class="btn btn-danger">
                        Yes, Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>