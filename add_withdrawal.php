<?php
    session_start();
    include 'config.php';

    if (!isset($_SESSION["username"]) || $_SESSION["username"] == "") {
        header("Location: index.php");
        exit;
    }

    /* =========================
    INITIAL VALUES
    ========================= */
    $withdrawerName = $_SESSION['withdrawerName'] ?? "";
    $withdrawerId   = $_SESSION['withdrawerId'] ?? "";
    $accNo          = $_SESSION['accNo'] ?? "";
    $currentDate    = date('Y-m-d');
    $balance        = $_SESSION['balance'] ?? 0;
    $warningMessage = "";

    /* =========================
    SEARCH ACCOUNT
    ========================= */
    if (isset($_POST['cmdSearch'])) {
        $accNo = $_POST['txtAccNo'];
        if (!empty($accNo)) {
            $query = "SELECT id, lastname, firstname, middlename FROM tbl_profile WHERE account_number = '$accNo'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION['withdrawerId'] = $row['id'];
                $_SESSION['withdrawerName'] = $row['lastname'] . ", " . $row['firstname'] . " " . $row['middlename'];
                $_SESSION['accNo'] = $accNo;

                /* CALCULATE BALANCE */
                $depositQuery = "SELECT SUM(amount) AS total_deposit FROM tbl_transaction WHERE profile_id = '{$row['id']}' AND trans_type = 'Deposit'";

                $depositResult = mysqli_query($conn, $depositQuery);
                $depositRow = mysqli_fetch_assoc($depositResult);
                $balance = $depositRow['total_deposit'] ?? 0;

                $withdrawalQuery = "SELECT SUM(amount) AS total_withdrawal FROM tbl_transaction WHERE profile_id = '{$row['id']}' AND trans_type = 'Withdrawal'";
                $withdrawalResult = mysqli_query($conn, $withdrawalQuery);
                $withdrawalRow = mysqli_fetch_assoc($withdrawalResult);
                $balance -= $withdrawalRow['total_withdrawal'] ?? 0;
                $_SESSION['balance'] = $balance;
            } else {
                $_SESSION['withdrawerName'] = "No account holder found";
                $_SESSION['withdrawerId'] = "";
                $_SESSION['balance'] = 0;
            }
        }
    }
    /* =========================
    WITHDRAW SUBMIT
    ========================= */
    if (isset($_POST['cmdWithdrawal'])) {
        $balance = $_SESSION['balance'] ?? 0;
        $date   = $_POST["txtDate"];
        $amount = $_POST["txtAmount"];
        $remarks = $_POST["txtRemarks"];
        $accNo  = $_POST["txtAccNo"];
        $withdrawerId = $_POST["withdrawerId"];

        if (empty($accNo)) {
            $warningMessage = "Account number cannot be empty.";
        } elseif (empty($withdrawerId)) {
            $warningMessage = "Please search for a valid account number.";
        } elseif ($amount > $balance) {
            $warningMessage = "Insufficient balance.";
        } else {
            $query = "INSERT INTO tbl_transaction (trans_date, profile_id, amount, remarks, trans_type, account_number) VALUES ('$date', '$withdrawerId', '$amount', '$remarks', 'Withdrawal', '$accNo')";
            if (mysqli_query($conn, $query)) {
                $_SESSION["page_result"] = "Withdrawal Successful";
                header("Location: main.php?page=withdrawal");
                exit;
            } else {
                $warningMessage = "Error: " . mysqli_error($conn);
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
    <title>Add Withdrawal</title>
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
        .input-group-text {
            background: #1d3557;
            color: white;
            border: none;
        }
        .balance-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 10px;
            font-weight: bold;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-md bank-card">
                        <!-- HEADER -->
                        <div class="card-header">
                            Add Withdrawal
                        </div>
                        <div class="card-body p-4">
                            <!-- WARNING -->
                            <?php if (!empty($warningMessage)): ?>
                                <div class="alert alert-danger">
                                    <?php echo $warningMessage; ?>
                                </div>
                            <?php endif; ?>
                            <div class="mb-3">
                                <label class="form-label">Transaction Date</label>
                                <input type="date" class="form-control form-control-md" name="txtDate" value="<?php echo $currentDate; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Account Number</label>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-md"
                                        name="txtAccNo"
                                        value="<?php echo $accNo; ?>">
                                    <button type="submit" class="btn btn-md btn-primary" name="cmdSearch">
                                        Search
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Account Holder</label>
                                <input type="text" class="form-control form-control-md" value="<?php echo $withdrawerName; ?>" readonly>
                                <input type="hidden" name="withdrawerId"
                                    value="<?php echo $withdrawerId; ?>">
                            </div>
                            <?php if ($withdrawerId): ?>
                                <div class="balance-box mb-3">
                                    Remaining Balance: ₱<?php echo number_format($balance, 2); ?>
                                </div>
                            <?php endif; ?>
                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="text" class="form-control form-control-md" name="txtAmount">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control form-control-md"
                                    name="txtRemarks" rows="2"></textarea>
                            </div>
                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <button type="submit" class="btn btn-success px-5" name="cmdWithdrawal">
                                    Save Withdrawal
                                </button>
                                <a href="main.php?page=withdrawal" class="btn btn-danger px-5">
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