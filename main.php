<?php
    session_start();
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
    <title>MYBANK</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container mt-4">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $allowed_pages = ['home', 'user_profile', 'deposit', 'withdrawal'];
            if (in_array($page, $allowed_pages)) {
                include "$page.php";
            } else {
                echo "<h1>Page not found</h1>";
            }
        } else {
            include 'home.php';
        }
        ?>
    </div>
</body>
</html>