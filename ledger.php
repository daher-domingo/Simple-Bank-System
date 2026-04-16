<?php
session_start();
    include 'config.php';
    if (!isset($_SESSION["username"]) || $_SESSION["username"] == "") {
        header("Location: index.php");
        exit;
    }

    $account_number = $_GET['account_number'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $balance = 0;
    $account_name = "Account not found";

    if ($account_number != "") {
        $account_query = "SELECT * FROM tbl_profile WHERE account_number = '$account_number'";
        $result = mysqli_query($conn, $account_query);
        if ($result && mysqli_num_rows($result) > 0) {
            $get_info = mysqli_fetch_assoc($result);
            $account_name = $get_info['lastname'] . ', ' . $get_info['firstname'] . ' ' . $get_info['middlename'];
        }
    }
    if (isset($_POST['cmd_reset'])) {
        $start_date = '';
        $end_date = '';
    }
    $crit = "";
    if (!isset($_POST['cmd_search']) && (!$start_date || !$end_date)) {
        $date_query = "
            SELECT 
                MIN(CASE WHEN trans_type = 'Deposit' THEN trans_date END) AS first_deposit,
                MAX(CASE WHEN trans_type = 'Withdrawal' THEN trans_date END) AS last_withdrawal
            FROM tbl_transaction WHERE account_number = '$account_number'
        ";

        $date_result = mysqli_query($conn, $date_query);
        $date_info = mysqli_fetch_assoc($date_result);

        $start_date = $start_date ?: ($date_info['first_deposit'] ?? '');
        $end_date = $end_date ?: ($date_info['last_withdrawal'] ?? '');
    }
    if ($start_date && $end_date) {
        $crit = "AND trans_date BETWEEN '$start_date' AND '$end_date'";
    }
    $query = "SELECT * FROM tbl_transaction WHERE account_number = '$account_number' $crit ORDER BY trans_date ASC";
    $transactions = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Ledger</title>
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
            font-size: 24px;
            font-weight: bold;
            padding: 18px;
        }
        .amount-deposit {
            color: #198754;
            font-weight: bold;
        }
        .amount-withdraw {
            color: #dc3545;
            font-weight: bold;
        }
        .balance {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="mb-3">
                    <a href="main.php?page=user_profile" class="btn btn-outline-secondary">
                        ← Back
                    </a>
                </div>
                <div class="card shadow-lg bank-card">
                    <!-- HEADER -->
                    <div class="card-header">
                        Account Ledger
                    </div>
                    <div class="card-body p-4">
                        <!-- ACCOUNT INFO -->
                        <div class="mb-3">
                            <p class="mb-1"><strong>Account No:</strong> <?php echo $account_number; ?></p>
                            <p class="mb-1"><strong>Name:</strong> <?php echo $account_name; ?></p>
                        </div>
                        <!-- FILTER -->
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?account_number=' . $account_number; ?>">
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text">From</span>
                                        <input type="date" class="form-control me-1" name="start_date" value="<?php echo $start_date; ?>">
                                        <span class="input-group-text">To</span>
                                        <input type="date" class="form-control me-1" name="end_date" value="<?php echo $end_date; ?>">
                                        <button type="submit" class="btn btn-primary me-1" name="cmd_search">Search</button>
                                        <button type="submit" class="btn btn-danger" name="cmd_reset">Reset</button>
                                    </div>
                                </div>
                                <!-- PRINT -->
                                <div class="col-md-4 text-end">
                                    <a href="print.php?account_number=<?php echo $account_number; ?>" class="btn btn-md btn-outline-success w-25">
                                        <i class="bi bi-printer-fill"></i>
                                        Print
                                    </a>
                                </div>
                            </div>
                        </form>
                        <!-- TABLE -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Date</th>
                                        <th>Deposit</th>
                                        <th>Withdrawal</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($transactions && mysqli_num_rows($transactions) > 0) {
                                        while ($record = mysqli_fetch_array($transactions)) {
                                            $trans_date = $record['trans_date'];
                                            $amount = $record['amount'];
                                            $type = $record['trans_type'];

                                            $deposit = ($type == 'Deposit') ? $amount : 0;
                                            $withdrawal = ($type == 'Withdrawal') ? $amount : 0;

                                            $balance += $deposit - $withdrawal;
                                    ?>
                                            <tr>
                                                <td><?php echo date('m-d-Y', strtotime($trans_date)); ?></td>
                                                <td class="amount-deposit">
                                                    <?php echo $deposit ? '₱ ' . number_format($deposit, 2) : '-'; ?>
                                                </td>
                                                <td class="amount-withdraw">
                                                    <?php echo $withdrawal ? '₱ ' . number_format($withdrawal, 2) : '-'; ?>
                                                </td>
                                                <td class="balance">
                                                    ₱ <?php echo number_format($balance, 2); ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='text-muted'>No transactions found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>