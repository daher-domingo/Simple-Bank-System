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

    $crit = "";

    if (!$start_date || !$end_date) {
        $date_query = "
            SELECT 
                MIN(CASE WHEN trans_type = 'Deposit' THEN trans_date END) AS first_deposit,
                MAX(CASE WHEN trans_type = 'Withdrawal' THEN trans_date END) AS last_withdrawal
            FROM tbl_transaction 
            WHERE account_number = '$account_number'
        ";
        $date_result = mysqli_query($conn, $date_query);
        $date_info = mysqli_fetch_assoc($date_result);

        $start_date = $start_date ?: ($date_info['first_deposit'] ?? '');
        $end_date = $end_date ?: ($date_info['last_withdrawal'] ?? '');
    }
    if ($start_date && $end_date) {
        $crit = "AND trans_date BETWEEN '$start_date' AND '$end_date'";
    }
    $query = "SELECT * FROM tbl_transaction 
            WHERE account_number = '$account_number' $crit 
            ORDER BY trans_date ASC";

    $transactions = mysqli_query($conn, $query);

    $from_text = date('m-d-Y', strtotime($start_date));
    $to_text = date('m-d-Y', strtotime($end_date));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Account Ledger</title>
    <style>
        body {
            background: #f1f5f9;
            font-family: "Times New Roman", serif;
        }
        .ledger-card {
            border: 1px solid #000;
            padding: 20px;
        }
        .ledger-header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }
        .ledger-header h2 {
            margin: 0;
            font-weight: bold;
        }
        .sub-info {
            font-size: 15px;
        }
        .table-ledger th, .table-ledger td {
            border: 1px solid #000 !important;
            font-size: 14px;
        }
        .table-ledger thead {
            background: #e9ecef;
        }
        .balance {
            font-weight: bold;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }
        .line {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="ledger-card bg-white">
                    <!-- HEADER -->
                    <div class="ledger-header">
                        <h2>ACCOUNT LEDGER</h2>
                        <div class="sub-info">Bank Transaction Statement</div>
                    </div>
                    <!-- ACCOUNT INFO -->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="sub-info">
                                <strong>Account No:</strong> <?php echo $account_number; ?><br>
                                <strong>Name:</strong> <?php echo $account_name; ?>
                            </div>
                        </div>
                        <div class="col text-end">
                            <div class="sub-info">
                                <strong>From:</strong> <?php echo $from_text; ?><br>
                                <strong>To:</strong> <?php echo $to_text; ?>
                            </div>
                        </div>
                    </div>
                    <!-- TABLE -->
                    <table class="table table-ledger text-center">
                        <thead>
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
                                        <td><?php echo $deposit ? '₱ ' . number_format($deposit, 2) : '-'; ?></td>
                                        <td><?php echo $withdrawal ? '₱ ' . number_format($withdrawal, 2) : '-'; ?></td>
                                        <td class="balance">₱ <?php echo number_format($balance, 2); ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='4'>No transactions found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- SUMMARY -->
                    <div class="mt-3">
                        <strong>Total Balance:</strong>
                        ₱ <?php echo number_format($balance, 2); ?>
                    </div>
                    <!-- SIGNATURE -->
                    <div class="signature-section">
                        <div class="line">
                            Prepared By<br>
                        </div>
                        <div class="line">
                            Checked By<br>
                        </div>
                        <div class="line">
                            Approved By
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>