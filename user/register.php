<?php
include_once('../config/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Check if email already exists
    $checkEmailQuery = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Email is already registered. Please use a different email or log in.";
    } else {
        // Check if passwords match
        if ($password !== $cpassword) {
            $error = "Passwords do not match. Please try again.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(15));

            // Insert new user data into the database
            $sql = "INSERT INTO users (full_name, email, mobile_no, password, token) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $full_name, $email, $mobile, $hashed_password, $token);

            if ($stmt->execute()) {
                header("Location: user_login.php");
                exit();
            } else {
                $error = "Error registering user. Please try again.";
            }
        }
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
    <title>Driver Registration</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --graystone: #1c1c1c;
            --teal: #008080;
            --white: #ffffff;
            --light-gray: #f8f9fa;
        }

        body,
        html {
            height: 100%;
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

        .registration-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .registration-card {
            width: 100%;
            max-width: 500px;
            padding: 30px;
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .registration-card h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--graystone);
            font-weight: 500;
        }

        .alert-message {
            text-align: center;
            color: red;
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: 500;
            color: var(--graystone);
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

        .registration-card .btn {
            padding: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Driver Panel</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>
        </div>
    </nav>

    <!-- Registration Form -->
    <div class="registration-container">
        <div class="registration-card">
            <h2>Driver Registration</h2>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile Number</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="cpassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="cpassword" name="cpassword" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>