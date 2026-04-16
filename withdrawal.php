<?php 
    include 'config.php';

    if (!isset($_SESSION["username"]) || $_SESSION["username"] == "") {
        header("location:index.php");
        exit;
    }
    if (isset($_POST['cmd_reset'])) {
        unset($_POST['start_date']);
        unset($_POST['end_date']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Withdrawal Records</title>
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
        .table thead {
            background: #1d3557;
            color: white;
        }
        .btn-bank {
            border-radius: 10px;
        }
        .summary-text {
            font-weight: bold;
        }
        .amount {
            color: #dc3545;
            font-weight: bold;
        }
        .container-card {
            margin-top: 30px;
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
    </style>
</head>
<body>
<!-- SUCCESS ALERT -->
<?php if (@$_SESSION["page_result"] == "Withdrawal Successful") { ?>
    <div class="alert alert-success alert-dismissible fade show text-center m-3 auto-hide">
        Withdrawal Record Successfully Added!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php unset($_SESSION["page_result"]); } ?>
<div class="container container-card">
    <div class="card shadow-lg bank-card">
        <!-- HEADER -->
        <div class="bank-header">
            Withdrawal Transaction Records
        </div>
        <div class="card-body p-4">
            <!-- FILTER -->
            <form method="POST" action="main.php?page=withdrawal">
                <div class="row mb-4 align-items-center">
                    <div class="col-md-6"></div>
                    <div class="col-md-6 text-end">
                        <div class="input-group shadow-sm">
                            <span class="input-group-text">From</span>
                            <input type="date" class="form-control me-1" name="start_date" value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : ''; ?>">
                            <span class="input-group-text">To</span>
                            <input type="date" class="form-control me-1" name="end_date" value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : ''; ?>">
                            <button type="submit" class="btn btn-primary me-1" name="cmd_search">Search</button>
                            <button type="submit" class="btn btn-danger" name="cmd_reset">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- TABLE -->
            <div class="table-responsive">
                <div class="col-md-4 mb-2">
                    <a href="add_withdrawal.php" class="btn btn-primary btn-md btn-bank">
                        + New Withdrawal
                    </a>
                </div>
                <table class="table table-hover table-bordered align-middle">
                    <thead class="text-center">
                        <tr>
                            <th>Date</th>
                            <th>Account No.</th>
                            <th>Account Holder Name</th>
                            <th>Amount</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $totalAmount = 0;
                    $totalRecords = 0;

                    $query = "SELECT tbl_transaction.trans_date, 
                                     tbl_profile.account_number AS profile_account_number, 
                                     tbl_profile.lastname, 
                                     tbl_profile.firstname, 
                                     tbl_profile.middlename, 
                                     tbl_transaction.amount, 
                                     tbl_transaction.remarks 
                              FROM tbl_transaction
                              JOIN tbl_profile ON tbl_transaction.profile_id = tbl_profile.id
                              WHERE tbl_transaction.trans_type = 'Withdrawal'";

                    if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
                        $startDate = $_POST['start_date'];
                        $endDate = $_POST['end_date'];
                        if (!empty($startDate) && !empty($endDate)) {
                            $query .= " AND trans_date BETWEEN '$startDate' AND '$endDate'";
                        }
                    }

                    $query .= " ORDER BY trans_date";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while($record = mysqli_fetch_array($result)) {

                            $name = $record["lastname"] . ', ' . $record['firstname'] . ' ' . $record['middlename'];
                            $amount = $record["amount"];
                            $remarks = $record["remarks"] ?? '';

                            $totalAmount += $amount;
                            $totalRecords++;
                    ?>
                    <tr>
                        <td><?php echo $record["trans_date"]; ?></td>
                        <td><?php echo $record["profile_account_number"] ?? 'N/A'; ?></td>
                        <td><?php echo $name; ?></td>
                        <td class="amount">₱<?php echo number_format($amount, 2); ?></td>
                        <td><?php echo $remarks; ?></td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center text-muted'>No records found.</td></tr>";
                    }
                    ?>
                    <!-- SUMMARY -->
                    <tr class="table-light">
                        <td colspan="3" class="text-end summary-text">Total Records:</td>
                        <td colspan="2" class="summary-text"><?php echo $totalRecords; ?></td>
                    </tr>
                    <tr class="table-light">
                        <td colspan="3" class="text-end summary-text">Total Amount:</td>
                        <td colspan="2" class="summary-text amount">
                            ₱<?php echo number_format($totalAmount, 2); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="col-md-12 mb-2 text-end">
                    <a href="add_withdrawal.php" class="btn btn-primary btn-md btn-bank">
                        + New Withdrawal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>