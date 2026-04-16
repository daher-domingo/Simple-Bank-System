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
    <title>Add User Profiles</title>
    <style>
        body {
            background: #f1f5f9;
        }
        .bank-card {
            border-radius: 15px;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #1d3557, #457b9d);
            color: white;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            padding: 18px;
        }
        .form-label {
            font-weight: 600;
        }
        .btn-bank {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <?php
    $error_message = "";
    if (isset($_POST['cmd_save'])) {
        $account_number = $_POST['account_number'];
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $birthday = $_POST['birthdate'];
        $gender = $_POST['gender'];
        $check_query = "SELECT * FROM tbl_profile WHERE account_number = '$account_number'";
        $result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($result) > 0) {
            $error_message = "Account number already exists.";
        } else {
            $query = "INSERT INTO tbl_profile (account_number, lastname, firstname, middlename, birthdate, gender) VALUES ('$account_number', '$lastname', '$firstname', '$middlename', '$birthday', '$gender')";
            if (mysqli_query($conn, $query)) {
                $_SESSION["page_result"] = "Add success";
                header("Location: main.php?page=user_profile");
                exit();
            } else {
                $error_message = "Error: " . mysqli_error($conn);
            }
        }
    }
    ?>
    <form method="POST">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-lg bank-card">
                        <!-- HEADER -->
                        <div class="card-header">
                            Add New Bank Member
                        </div>
                        <div class="card-body p-4">
                            <!-- ERROR -->
                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-warning text-center">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>     
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Account Number</label>
                                    <input type="number" class="form-control form-control-md" name="account_number" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Lastname</label>
                                    <input type="text" class="form-control form-control-md" name="lastname" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Firstname</label>
                                    <input type="text" class="form-control form-control-md" name="firstname" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Middlename</label>
                                    <input type="text" class="form-control form-control-md" name="middlename" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Birthdate</label>
                                    <input type="date" class="form-control form-control-md" name="birthdate" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select form-select-md" name="gender" required>
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <!-- BUTTONS -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg btn-bank px-5"name="cmd_save">
                                    Save
                                </button>
                                <a href="main.php?page=user_profile" class="btn btn-danger btn-lg btn-bank px-5">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>