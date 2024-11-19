<?php
session_start();
include_once('../config/db_connection.php');

// Check if traffic police is logged in
if (!isset($_SESSION['traffic_police_id'])) {
    header("Location: traffic_police_login.php");
    exit();
}

// Initialize message variable
$message = '';

// Fetch challan details
$total_challans_sql = "SELECT COUNT(*) as total FROM challans";
$total_challans_result = $conn->query($total_challans_sql);
$total_challans = $total_challans_result->fetch_assoc()['total'];

$pending_challans_sql = "SELECT COUNT(*) as total FROM challans WHERE status = 'Unpaid'";
$pending_challans_result = $conn->query($pending_challans_sql);
$pending_challans = $pending_challans_result->fetch_assoc()['total'];

$completed_challans_sql = "SELECT COUNT(*) as total FROM challans WHERE status = 'Paid'";
$completed_challans_result = $conn->query($completed_challans_sql);
$completed_challans = $completed_challans_result->fetch_assoc()['total'];

// Fetch users and offences for the form
$users = $conn->query("SELECT id, full_name FROM users");
$offences = $conn->query("SELECT id, description FROM offences");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $offence_id = $_POST['offence_id'];
    $amount = $_POST['amount'];
    $police_id = $_SESSION['traffic_police_id']; // Dynamically set police ID from session

    // Fetch user's email address
    $user_email_sql = "SELECT email FROM users WHERE id = $user_id";
    $user_email_result = $conn->query($user_email_sql);
    $user_email = $user_email_result->fetch_assoc()['email'];

    // Fetch offense description
    $offence_description = $conn->query("SELECT description FROM offences WHERE id = $offence_id")->fetch_assoc()['description'];

    // Insert challan into database
    $stmt = $conn->prepare("INSERT INTO challans (user_id, police_id, offence_description, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisd", $user_id, $police_id, $offence_description, $amount);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Challan added successfully!</div>";

        // Send email to the user
        $to = $user_email;
        $subject = "Challan Issued: Traffic Violation";
        $email_message = "Dear user,\n\nA challan has been issued to you for the following offense:\n";
        $email_message .= "Offense: $offence_description\n";
        $email_message .= "Amount: $amount\n\n";
        $email_message .= "Please pay your challan as soon as possible.\n\n";
        $email_message .= "Thank you,\nTraffic Police Department";

        $headers = "From: 21omadsul@gmail.com";
        // PHP mail function to send the email
        mail($to, $subject, $email_message, $headers);
    } else {
        $message = "<div class='alert alert-danger'>Error adding challan: " . $stmt->error . "</div>";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Police Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --off-black: #8c8c8c;
            --graystone: #1c1c1c;
            --teal: #008080;
            --white: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('../raybay-kG71BXh8KFw-unsplash.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: var(--graystone);
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: var(--graystone);
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background-color: var(--white);
            color: var(--graystone);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        h4 {
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .list-group-item {
            background-color: var(--off-black);
            color: var(--white);
            border: none;
            font-weight: 400;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .btn-add-challan {
            display: block;
            width: 100%;
            background-color: var(--graystone);
            color: var(--white);
            border: none;
            padding: 15px;
            font-weight: 500;
            font-size: 1.1rem;
            border-radius: 8px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .btn-add-challan:hover {
            background-color: var(--teal);
            color: var(--graystone);
        }

        .challan-form {
            margin-top: 40px;
        }

        .form-group label {
            font-weight: 500;
        }

        .select2-container {
            width: 100% !important;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .btn-add-challan {
                padding: 12px;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Traffic Police Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Welcome</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="traffic_police_logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h4>Challan Summary</h4>

        <!-- Display message here -->
        <?php if ($message): ?>
            <?= $message; ?>
        <?php endif; ?>

        <ul class="list-group">
            <li class="list-group-item">Total Challans: <?= $total_challans ?></li>
            <li class="list-group-item">Pending Challans: <?= $pending_challans ?></li>
            <li class="list-group-item">Completed Challans: <?= $completed_challans ?></li>
        </ul>
        <a href="#addChallanForm" class="btn-add-challan">Add New Challan</a>

        <!-- Challan Form -->
        <div class="challan-form" id="addChallanForm">
            <h4>Add New Challan</h4>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="user_id">Driver's Name</label>
                    <select id="user_id" name="user_id" class="form-control select2" required>
                        <option value="">Select Driver</option>
                        <?php while ($user = $users->fetch_assoc()): ?>
                            <option value="<?= $user['id'] ?>"><?= $user['full_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label for="offence_id">Offense Description</label>
                    <select id="offence_id" name="offence_id" class="form-control" required>
                        <option value="">Select Offense</option>
                        <?php while ($offence = $offences->fetch_assoc()): ?>
                            <option value="<?= $offence['id'] ?>"><?= $offence['description'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label for="amount">Amount</label>
                    <input type="number" id="amount" name="amount" class="form-control" step="0.01" min="0" required>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Add Challan</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Smooth scrolling when the "Add New Challan" button is clicked
            document.querySelector('.btn-add-challan').addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('#addChallanForm').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>