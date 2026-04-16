<?php
    session_start();
    include 'config.php';
    if (!isset($_SESSION["username"]) || $_SESSION["username"] == "") {
        header("Location: index.php");
        exit;
    }
    $depositorName = "";
    $depositorId = "";
    $accNo = "";
    $currentDate = date('Y-m-d');

    if (isset($_POST['cmdSearch'])) {
        $accNo = $_POST['txtAccNo'];
        $query = "SELECT id, lastname, firstname, middlename FROM tbl_profile WHERE account_number = '$accNo'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $depositorId = $row['id'];
            $depositorName = $row['lastname'] . ", " . $row['firstname'] . " " . $row['middlename'];
        } else {
            $depositorName = "No depositor found";
        }
    }
    if (isset($_POST['cmdDeposit'])) {
        $date = $_POST["txtDate"];
        $amount = $_POST["txtAmount"];
        $remarks = $_POST["txtRemarks"];
        $transType = "Deposit";
        $accNo = $_POST["txtAccNo"];
        $depositorId = $_POST["depositorId"];

        if (empty($accNo)) {
            echo '<div class="alert alert-danger">Account number cannot be empty.</div>';
        } elseif (empty($depositorId)) {
            echo '<div class="alert alert-danger">Please search for a valid account number.</div>';
        } else {
            $query = "INSERT INTO tbl_transaction (trans_date, profile_id, amount, remarks, trans_type, account_number) 
                        VALUES ('$date', '$depositorId', '$amount', '$remarks', '$transType', '$accNo')";

            if (mysqli_query($conn, $query)) {
                $_SESSION["page_result"] = "Deposit Successful";
                header("Location:main.php?page=deposit");
                exit;
            } else {
                echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
            }
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
    <title>Add Deposit</title>
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
        .input-group-text {
            background: #1d3557;
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-md bank-card">
                        <!-- HEADER -->
                        <div class="card-header">
                            Add Deposit
                        </div>
                        <div class="card-body p-4">
                            <!-- DATE -->
                            <div class="mb-3">
                                <label class="form-label">Transaction Date</label>
                                <input type="date" class="form-control form-control-md"
                                    name="txtDate" value="<?php echo $currentDate; ?>" required>
                            </div>
                            <!-- ACCOUNT SEARCH -->
                            <div class="mb-3">
                                <label class="form-label">Account Number</label>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-md" name="txtAccNo" value="<?php echo $accNo; ?>" required>
                                    <button type="submit" class="btn btn-primary btn-bank" name="cmdSearch">
                                        Search
                                    </button>
                                </div>
                            </div>
                            <!-- NAME -->
                            <div class="mb-3">
                                <label class="form-label">Depositor Name</label>
                                <input type="text" class="form-control form-control-md" value="<?php echo $depositorName; ?>" readonly>
                                <input type="hidden" name="depositorId" value="<?php echo $depositorId; ?>">
                            </div>
                            <!-- AMOUNT -->
                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="text" class="form-control form-control-md" name="txtAmount" value="<?php echo @$amount; ?>">
                                </div>
                            </div>
                            <!-- REMARKS -->
                            <div class="mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control form-control-md"
                                    name="txtRemarks" rows="2"></textarea>
                            </div>
                            <!-- BUTTONS -->
                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <button type="submit" class="btn btn-success btn-md btn-bank px-5" name="cmdDeposit">
                                    Save Deposit
                                </button>
                                <a href="main.php?page=deposit" class="btn btn-danger btn-md btn-bank px-5">
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