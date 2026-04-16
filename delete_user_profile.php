<?php
    session_start();
    include 'config.php';

    if (!isset($_SESSION["username"]) || $_SESSION["username"] == "") {
        header("Location: index.php");
        exit;
    }
    if (!isset($_GET['id'])) {
        echo "<div class='alert alert-danger text-center'>Invalid or missing ID parameter!</div>";
        exit;
    }
    $rid = $_GET['id'];
    $query = "SELECT * FROM tbl_profile WHERE id = '$rid' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $record = mysqli_fetch_assoc($result);
        $account_number = $record['account_number'];
        $lastname = $record['lastname'];
        $firstname = $record['firstname'];
        $middlename = $record['middlename'];
        $birthday = $record['birthdate'];
        $gender = $record['gender'];
    } else {
        echo "<div class='alert alert-danger text-center'>Record not found!</div>";
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['cmd_delete']) && $_POST['cmd_delete'] == 'yes') {
            $deleteQuery = "DELETE FROM tbl_profile WHERE id = '$rid' LIMIT 1";
            $del_result = mysqli_query($conn, $deleteQuery);
            if ($del_result) {
                $_SESSION['page_result'] = "Delete success";
                header("Location: main.php?page=user_profile");
                exit();
            } else {
                echo "<div class='alert alert-danger text-center'>Error deleting record.</div>";
            }
        } else {
            header("Location: main.php?page=user_profile");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Delete Profile</title>
    <style>
        body {
            background: #f1f5f9;
        }
        .bank-card {
            border-radius: 15px;
            overflow: hidden;
        }
        .bank-header {
            background: linear-gradient(135deg, #1d3557, #457b9d);
            color: white;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            padding: 18px;
        }
        .btn-bank {
            border-radius: 10px;
        }
        .danger-text {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg bank-card">
                    <!-- HEADER -->
                    <div class="bank-header bg-danger">
                        Delete User Profile
                    </div>
                    <div class="card-body p-4">
                        <p class="text-center mb-4 danger-text">
                            Are you sure you want to delete this record?
                        </p>
                        <form method="POST" action="delete_user_profile.php?id=<?php echo $rid; ?>">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-danger">
                                        <tr>
                                            <th>Account No.</th>
                                            <th>Lastname</th>
                                            <th>Firstname</th>
                                            <th>Middlename</th>
                                            <th>Birthday</th>
                                            <th>Gender</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $account_number; ?></td>
                                            <td><?php echo $lastname; ?></td>
                                            <td><?php echo $firstname; ?></td>
                                            <td><?php echo $middlename; ?></td>
                                            <td><?php echo $birthday; ?></td>
                                            <td><?php echo $gender; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- BUTTONS -->
                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <button type="submit" name="cmd_delete" value="yes" class="btn btn-danger btn-lg btn-bank px-5">
                                    Yes, Delete
                                </button>
                                <a href="main.php?page=user_profile" class="btn btn-secondary btn-lg btn-bank px-5">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>