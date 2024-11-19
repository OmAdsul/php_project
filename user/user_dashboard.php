<?php
session_start();
include_once('../config/db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: user_login.php");
    exit();
}

// Get user details
$driver_id = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();
$driver = $result->fetch_assoc();

// Get challan details, unpaid first
$sql_challans = "SELECT * FROM challans WHERE user_id = ? ORDER BY FIELD(status, 'Unpaid', 'Paid')";
$stmt_challans = $conn->prepare($sql_challans);
$stmt_challans->bind_param("i", $driver_id);
$stmt_challans->execute();
$challans = $stmt_challans->get_result();

$stmt->close();
$stmt_challans->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

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

        .logout-btn {
            background-color: var(--teal);
            color: var(--white);
            padding: 8px 20px;
            border-radius: 20px;
            text-transform: uppercase;
            font-weight: 500;
        }

        .logout-btn:hover {
            background-color: #006666;
        }

        .container {
            max-width: 100%;
            margin: 40px auto;
            background-color: var(--white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-weight: 500;
            text-align: center;
            margin-bottom: 30px;
        }

        .card {
            background-color: var(--light-gray);
            border: none;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--graystone);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--teal);
        }

        table th,
        table td {
            text-align: center;
            padding: 12px;
        }

        .btn-success {
            background-color: var(--teal);
            border: none;
        }

        .btn-success:hover {
            background-color: #006666;
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
                margin: 10px;
            }

            .card {
                padding: 15px;
            }

            h2 {
                font-size: 1.2rem;
            }

            .card-title {
                font-size: 1.2rem;
            }

            p {
                font-size: 0.9rem;
            }

            .btn {
                font-size: 0.9rem;
            }
        }

        .table-responsive {
            margin-top: 20px;
        }

        @media (max-width: 576px) {
            .table thead {
                display: none;
            }

            .table td {
                display: block;
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">User Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link logout-btn" href="user_logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h2>Welcome to Your Dashboard</h2>

        <div class="card">
            <h5 class="card-title">Personal Details</h5>
            <p><strong>Name:</strong> <?= $driver['full_name'] ?></p>
            <p><strong>Email:</strong> <?= $driver['email'] ?></p>
            <p><strong>Mobile:</strong> <?= $driver['mobile_no'] ?></p>
            <a href="edit_driver.php?id=<?= $driver_id ?>" class="btn btn-primary">Edit</a>
        </div>

        <div>
            <h5>Challan Details</h5>
            <div class="table-responsive">
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Challan ID</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($challan = $challans->fetch_assoc()) : ?>
                            <tr>
                                <td data-label="Challan ID"><?= $challan['id'] ?></td>
                                <td data-label="Description"><?= $challan['offence_description'] ?></td>
                                <td data-label="Amount"><?= $challan['amount'] ?></td>
                                <td data-label="Status"><?= $challan['status'] ?></td>
                                <td data-label="Action">
                                    <?php if ($challan['status'] == 'Unpaid') : ?>
                                        <a href="payment_form.php" class="btn btn-success">Make Payment</a>
                                    <?php else : ?>
                                        <button class="btn btn-secondary" disabled>Paid</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>