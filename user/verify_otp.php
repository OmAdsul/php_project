<?php
session_start();

// Check if OTP was sent
if (!isset($_SESSION['otp'])) {
    header("Location: payment_form.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];

    // Verify OTP
    if ($entered_otp == $_SESSION['otp']) {
        // OTP is correct, redirect to process payment
        header("Location: process_payment.php");
        exit();
    } else {
        $error_message = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>

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
        <h2>OTP Verification</h2>
        <div class="card mt-4">
            <div class="card-body">
                <?php if (isset($error_message)) : ?>
                    <div class="alert alert-danger"><?= $error_message; ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="otp" class="form-label">Enter OTP</label>
                        <input type="text" class="form-control" id="otp" name="otp" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Verify OTP</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>