<?php
session_start();
include_once('../config/db_connection.php');

// Check if super admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $police_id = $_POST['police_id'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO traffic_police (police_id, name, mobile_no, address, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $police_id, $name, $mobile, $address, $password);

    if ($stmt->execute()) {
        echo "Traffic police officer added successfully";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: super_admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Traffic Police Officer</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --graystone: #1c1c1c;
            --teal: #008080;
            --white: #ffffff;
            --off-white: #f8f9fa;
            --dark-gray: #333333;
            --light-gray: #888888;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--off-white);
        }

        .navbar {
            background-color: var(--graystone);
        }

        .navbar .navbar-brand {
            color: var(--white);
            font-weight: 600;
            font-size: 24px;
        }

        .navbar .nav-link {
            color: var(--white);
        }

        #sidebar {
            background-color: var(--graystone);
            min-width: 220px;
            height: 100vh;
            transition: all 0.3s;
        }

        #sidebar .nav-link {
            color: var(--light-gray);
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 10px;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-item.active .nav-link {
            background-color: var(--teal);
            color: var(--white);
        }

        #main-content {
            margin-left: 220px;
            padding: 30px;
            width: calc(100% - 220px);
        }

        .form-control {
            border-radius: 5px;
            font-size: 14px;
        }

        .btn-primary {
            background-color: var(--teal);
            border-color: var(--teal);
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #006666;
            border-color: #006666;
        }

        @media (max-width: 768px) {
            #sidebar {
                min-width: 100%;
                height: auto;
                position: relative;
            }

            #main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand ms-3" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Welcome</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="super_admin_logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar and Main Content -->
    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item active">
                    <a class="nav-link" href="super_admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_traffic_police.php">Add Traffic Police Officer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_traffic_police.php">View & Manage Traffic Police Officers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_driver.php">View Driver Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="super_admin_logout.php">Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div id="main-content">
            <div class="container">
                <h4 class="mb-4">Add Traffic Police Officer</h4>
                <form action="add_traffic_police.php" method="POST">
                    <div class="form-group mb-3">
                        <label for="police_id">Police ID:</label>
                        <input type="text" class="form-control" id="police_id" name="police_id" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="mobile">Mobile No:</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Officer</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>