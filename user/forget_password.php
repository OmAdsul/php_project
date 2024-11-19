<?php
include_once('../config/db_connection.php');
$error = $success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Update the token in the user's records
        $sql = "UPDATE users SET token = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Send email to user with the password reset link
        $resetLink = "http://localhost/main-project/user/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click on this link to reset your password: " . $resetLink;
        $headers = "From: 21omadsul@gmail.com";

        if (mail($email, $subject, $message, $headers)) {
            $success = "A password reset link has been sent to your email.";
        } else {
            $error = "Failed to send the email. Please try again.";
        }
    } else {
        $error = "No account found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --graystone: #1c1c1c;
            --teal: #008080;
            --white: #ffffff;
            --off-white: #f8f9fa;
            --light-gray: #8c8c8c;
        }

        body,
        html {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background-color: var(--off-white);
            color: var(--graystone);
            display: flex;
            flex-direction: column;
            background: url('../raybay-kG71BXh8KFw-unsplash.jpg') no-repeat center center/cover;
        }

        .navbar {
            background-color: var(--graystone);
        }

        .navbar-brand,
        .nav-link {
            color: var(--white) !important;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
        }

        .card {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--graystone);
            font-weight: 500;
        }

        .form-label {
            color: var(--graystone);
            font-weight: 500;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-primary {
            background-color: var(--graystone);
            border: none;
            border-radius: 20px;
        }

        .btn-primary:hover {
            background-color: var(--teal);
        }

        .card .btn {
            padding: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .alert {
            text-align: center;
            font-weight: 500;
            padding: 10px;
        }

        .alert-danger {
            color: var(--white);
            background-color: var(--light-gray);
        }

        .alert-success {
            color: var(--white);
            background-color: var(--teal);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Driver Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Forgot Password Form -->
    <div class="container">
        <div class="card">
            <h2>Forgot Password</h2>
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <?php if (!empty($success)) : ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Enter Your Registered Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>