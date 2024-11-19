<?php
session_start();
include_once('../config/db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: user_login.php");
    exit();
}

// Get the challan details from the database
$sql_challans = "SELECT id, amount FROM challans WHERE user_id = ? and status='unpaid'";
$stmt_challans = $conn->prepare($sql_challans);
$stmt_challans->bind_param("i", $_SESSION['id']);
$stmt_challans->execute();
$result = $stmt_challans->get_result();
$challans = $result->fetch_assoc();

$user_mobile = "SELECT mobile_no FROM users WHERE id=?";
$stmt_user_mobile = $conn->prepare($user_mobile);
$stmt_user_mobile->bind_param("i", $_SESSION['id']);
$stmt_user_mobile->execute();
$result_user_mobile = $stmt_user_mobile->get_result();
$user_mobile_no = $result_user_mobile->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --graystone: #1c1c1c;
            --teal: #008080;
            --white: #ffffff;
            --light-gray: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('../raybay-kG71BXh8KFw-unsplash.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: var(--graystone);
        }

        .navbar {
            background-color: var(--graystone);
        }

        .navbar-brand,
        .nav-link {
            color: var(--white) !important;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: var(--white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 500;
            color: var(--graystone);
        }

        .form-label {
            font-weight: 500;
            color: var(--graystone);
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary {
            background-color: var(--graystone);
            border: none;
            padding: 10px 20px;
            text-transform: uppercase;
        }

        .btn-primary:hover {
            background-color: var(--teal);
        }

        .btn-secondary {
            background-color: #ddd;
            color: var(--graystone);
        }

        .btn-secondary:hover {
            background-color: #ccc;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Traffic Offense Platform</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="user_dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Make Payment</h2>
        <div class="card mt-4">
            <div class="card-body">
                <form action="send_otp.php" method="POST">
                    <div class="mb-3">
                        <label for="challan_id" class="form-label">Challan ID</label>
                        <input type="text" class="form-control" id="challan_id" name="challan_id" value="<?= $challans['id'] ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="text" class="form-control" id="amount" name="amount" value="<?= $challans['amount'] ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="mobile_number" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="<?= $user_mobile_no['mobile_no'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="card_type" class="form-label">Card Type</label>
                        <select class="form-control" id="card_type" name="card_type" required>
                            <option value="debit">Debit</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cvv" name="cvv" required>
                    </div>
                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="month" class="form-control" id="expiry_date" name="expiry_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Payment</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>