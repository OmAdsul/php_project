<?php
session_start();
include_once('../config/db_connection.php');

// Check if super admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: super_admin_login.php");
    exit();
}

// Fetch traffic police officers
$sql = "SELECT * FROM traffic_police";
$traffic_police_result = $conn->query($sql);

// Fetch drivers
$sql = "SELECT * FROM users";
$drivers_result = $conn->query($sql);

// Fetch challan details
$total_challans_sql = "SELECT COUNT(*) as total FROM challans";
$total_challans_result = $conn->query($total_challans_sql);
$total_challans = $total_challans_result->fetch_assoc()['total'];

$pending_challans_sql = "SELECT COUNT(*) as total FROM challans WHERE status = 'unpaid'";
$pending_challans_result = $conn->query($pending_challans_sql);
$pending_challans = $pending_challans_result->fetch_assoc()['total'];

$completed_challans_sql = "SELECT COUNT(*) as total FROM challans WHERE status = 'paid'";
$completed_challans_result = $conn->query($completed_challans_sql);
$completed_challans = $completed_challans_result->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
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

        #content {
            padding: 30px;
            background-color: var(--off-white);
            width: 100%;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: var(--teal);
            color: var(--white);
        }

        .card-title {
            font-weight: 600;
            color: var(--white);
        }

        .card-body p {
            font-size: 24px;
            font-weight: 500;
        }

        /* Media Queries for Responsive Design */
        @media (max-width: 1200px) {
            #sidebar {
                min-width: 100%;
                height: auto;
                position: relative;
            }

            .card-body p {
                font-size: 22px;
            }
        }

        @media (max-width: 768px) {
            #content {
                padding: 20px;
            }

            .card-body p {
                font-size: 20px;
            }
        }

        @media (max-width: 576px) {
            .navbar .navbar-brand {
                font-size: 20px;
            }

            .card-body p {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .card-body p {
                font-size: 16px;
            }

            h2 {
                font-size: 20px;
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

    <!-- Main Wrapper -->
    <div class="wrapper d-flex">
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
            </ul>
        </nav>

        <!-- Content -->
        <div id="content">
            <div class="container">
                <h2 class="mb-4">Super Admin Dashboard</h2>

                <!-- Summary of Challans -->
                <div class="row mb-4">
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                        <div class="card text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Challans</h5>
                                <p class="card-text"><?= $total_challans ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                        <div class="card text-white">
                            <div class="card-body">
                                <h5 class="card-title">Pending Challans</h5>
                                <p class="card-text"><?= $pending_challans ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                        <div class="card text-white">
                            <div class="card-body">
                                <h5 class="card-title">Completed Challans</h5>
                                <p class="card-text"><?= $completed_challans ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>