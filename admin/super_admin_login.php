<?php
session_start();
include_once('../config/db_connection.php');

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $uname_search = "SELECT * FROM super_admins WHERE username='$username' AND password='$password'";
    $query = mysqli_query($conn, $uname_search);

    $uname_count = mysqli_num_rows($query);

    if ($uname_count == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header('Location: super_admin_dashboard.php');
        exit();
    } else {
        $error_message = "Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login</title>

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
        }

        .navbar {
            background-color: var(--graystone);
        }

        .navbar-brand,
        .nav-link {
            color: var(--white) !important;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            background: url('../raybay-kG71BXh8KFw-unsplash.jpg') no-repeat center center/cover;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .login-card h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--graystone);
            font-weight: 500;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
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

        .login-card .btn {
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
            <a class="navbar-brand" href="#">Admin Panel</a>
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

    <!-- Login Form -->
    <div class="login-container">
        <div class="login-card">
            <h2>Super Admin Login</h2>
            <?php if (isset($error_message)) : ?>
                <div class="error-message"><?= $error_message ?></div>
            <?php endif; ?>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>