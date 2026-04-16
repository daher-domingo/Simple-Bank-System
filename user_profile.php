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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>List of Members</title>
    <style>
        body {
            background: #f1f5f9;
        }
        .bank-header {
            background: linear-gradient(135deg, #1d3557, #457b9d);
            color: white;
            padding: 20px;
            font-weight: bold;
            font-size: 22px;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }
        .bank-card {
            border-radius: 15px;
            overflow: hidden;
        }
        .btn-bank {
            border-radius: 10px;
        }
        .table thead {
            background: #1d3557;
            color: white;
        }
        .amount {
            font-weight: bold;
            color: #198754;
        }
        .auto-hide {
            animation: fadeOut 1s ease forwards;
            animation-delay: 3s;
        }
        @keyframes fadeOut {
            to {
                opacity: 0;
                visibility: hidden;
                height: 0;
                margin: 0;
                padding: 0;
            }
        }
        .search-box {
            border-radius: 10px;
        }
        .account-link {
            text-decoration: none;
            color: #000;
            font-weight: bold;
        }
        .account-link:hover {
            text-decoration: underline;
            color: #1d3557;
        }
    </style>
</head>
<body>
    <?php
    $alertType = "";
    $alertMessage = "";

    switch (@$_SESSION["page_result"]) {
        case "Add success":
            $alertType = "alert-success";
            $alertMessage = "Record successfully added!";
            break;
        case "Update successful":
            $alertType = "alert-success";
            $alertMessage = "Record successfully edited!";
            break;
        case "Delete success":
            $alertType = "alert-success";
            $alertMessage = "Record successfully deleted!";
            break;
    }
    if ($alertMessage) {
        echo "<div class='alert $alertType alert-dismissible fade show text-center m-3 auto-hide'>
        $alertMessage
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>";
    }
    unset($_SESSION["page_result"]);
    $query = "SELECT * FROM tbl_profile";
    $keyword = "";
    if (isset($_POST['cmd_search'])) {
        $keyword = $_POST['search'];
        $query .= " WHERE account_number LIKE '$keyword%' 
                OR lastname LIKE '$keyword%' 
                OR firstname LIKE '$keyword%'";
    } elseif (isset($_POST['cmd_reset'])) {
        $keyword = "";
    }
    $result = mysqli_query($conn, $query);
    ?>
    <div class="container py-4">
        <div class="card shadow-lg bank-card">
            <div class="bank-header">
                List of Bank Members
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-6"></div>
                        <div class="col-md-6 text-end">
                            <div class="input-group shadow-sm text-end">
                                <input type="search" class="form-control form-control-md search-box me-1" name="search" placeholder="Search account / name..." value="<?php echo @$keyword; ?>">
                                <button type="submit" class="btn btn-primary btn-bank me-1" name="cmd_search">
                                    Search
                                </button>
                                <button type="submit" class="btn btn-danger btn-bank" name="cmd_reset">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- TABLE -->
                <div class="table-responsive">
                    <a href="add_user_profile.php" class="btn btn-success btn-md btn-bank mb-2 mt-0">
                        + Add New Member
                    </a>
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="text-center">
                            <tr>
                                <th>Account No.</th>
                                <th>Lastname</th>
                                <th>Firstname</th>
                                <th>Middlename</th>
                                <th>Birthdate</th>
                                <th>Gender</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($record = mysqli_fetch_array($result)) {
                                    $id = $record["id"];
                                    $accountNumber = $record["account_number"];
                                    $lastname = $record["lastname"];
                                    $firstname = $record["firstname"];
                                    $middlename = $record["middlename"];
                                    $gender = $record["gender"];
                                    $birthdate = $record["birthdate"];
                            ?>
                                    <tr>
                                        <td>
                                            <a href="ledger.php?account_number=<?php echo $accountNumber; ?>"
                                                class="account-link">
                                                <?php echo $accountNumber; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $lastname; ?></td>
                                        <td><?php echo $firstname; ?></td>
                                        <td><?php echo $middlename; ?></td>
                                        <td><?php echo $birthdate; ?></td>
                                        <td><?php echo $gender; ?></td>
                                        <td class="text-center">
                                            <a href="edit_user_profile.php?ref=<?php echo $id; ?>" class="btn btn-primary btn-sm btn-bank">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <a href="delete_user_profile.php?id=<?php echo $id; ?>" class="btn btn-outline-danger btn-sm btn-bank">
                                                <i class="bi bi-trash3"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center text-muted'>No records found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- FOOTER BUTTON -->
                <div class="text-end mt-3">
                    <a href="add_user_profile.php"
                        class="btn btn-success btn-md btn-bank">
                        + Add New Member
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>