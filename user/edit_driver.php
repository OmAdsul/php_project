<?php
session_start();
include_once('../config/db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: user_login.php");
    exit();
}

// Initialize variables
$driver = null;
$error_message = "";

// Fetch the driver's details using the ID from the URL
if (isset($_GET['id'])) {
    $driver_id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $driver_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $driver = $result->fetch_assoc();
    $stmt->close();

    // Check if driver data was found
    if (!$driver) {
        $error_message = "Driver details not found.";
    }
} else {
    $error_message = "Invalid request. Driver ID is missing.";
}

// Handle form submission for updating driver details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $driver) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];

    // Update the driver details in the database
    $sql = "UPDATE users SET full_name = ?, email = ?, mobile_no = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $full_name, $email, $mobile, $driver_id);

    if ($stmt->execute()) {
        header("Location: user_dashboard.php"); // Redirect to the dashboard after updating
        exit();
    } else {
        $error_message = "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Driver</title>

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
            /* background-color: var(--light-gray); */
            background-image: url('../raybay-kG71BXh8KFw-unsplash.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: var(--graystone);
            padding-top: 0px;
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
            background-color: var(--white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            font-weight: 500;
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-primary {
            background-color: var(--graystone);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: var(--teal);
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-secondary:hover {
            background-color: #565e64;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
        }

        .alert {
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
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
    <div class="container">
        <h2>Edit Driver</h2>

        <!-- Error message -->
        <?php if ($error_message) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php elseif ($driver) : ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name:</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($driver['full_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($driver['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile No:</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" value="<?= htmlspecialchars($driver['mobile_no']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Driver</button>
                <a href="user_dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>