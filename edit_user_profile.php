<?php
    session_start();
    include 'config.php';

    if (!isset($_SESSION["username"]) || $_SESSION["username"] == "") {
        header("Location: index.php");
        exit;
    }

    $error_message = "";
    $rid = $_REQUEST['ref'];

    $que = "SELECT * FROM tbl_profile WHERE id = '$rid' LIMIT 1";
    $myresult = mysqli_query($conn, $que);

    if (mysqli_num_rows($myresult) > 0) {
        $record = mysqli_fetch_array($myresult);
        $lastname = $record["lastname"];
        $firstname = $record["firstname"];
        $middlename = $record["middlename"];
        $birthday = $record["birthdate"];
        $gender = $record["gender"];
        $account_number = $record["account_number"];
    }

    if (isset($_POST['cmd_update'])) {
        $lastname = $_POST["lastname"];
        $firstname = $_POST["firstname"];
        $middlename = $_POST["middlename"];
        $birthday = $_POST["birthdate"];
        $gender = $_POST["gender"];

        $que = "UPDATE tbl_profile SET lastname = '$lastname', firstname = '$firstname', middlename = '$middlename', birthdate = '$birthday',  gender = '$gender' WHERE id = '$rid'";

        if (!mysqli_query($conn, $que)) {
            $_SESSION["page_result"] = "Update failed";
        } else {
            mysqli_close($conn);
            $_SESSION["page_result"] = "Update successful";
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

    <title>Edit Profile</title>

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
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg bank-card">
                    <!-- HEADER -->
                    <div class="card-header">
                        Edit Profile
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Account Number</label>
                            <input type="text" class="form-control form-control-lg"
                                value="<?php echo $account_number; ?>" readonly>
                        </div>
                        <!-- NAME FIELDS -->
                        <form method="POST" action="edit_user_profile.php?ref=<?php echo $rid; ?>">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Lastname</label>
                                    <input type="text" class="form-control form-control-md" name="lastname" value="<?php echo $lastname; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Firstname</label>
                                    <input type="text" class="form-control form-control-md" name="firstname" value="<?php echo $firstname; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Middlename</label>
                                    <input type="text" class="form-control form-control-md" name="middlename" value="<?php echo $middlename; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Birthdate</label>
                                    <input type="date" class="form-control form-control-md" name="birthdate" value="<?php echo $birthday; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select form-select-lg" name="gender" required>
                                        <option value="Male" <?php if ($gender == "Male") echo "selected"; ?>>Male</option>
                                        <option value="Female" <?php if ($gender == "Female") echo "selected"; ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            <!-- BUTTONS -->
                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <button type="submit" class="btn btn-success btn-lg btn-bank px-5" name="cmd_update">
                                    Update
                                </button>
                                <a href="main.php?page=user_profile" class="btn btn-danger btn-lg btn-bank px-5">
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