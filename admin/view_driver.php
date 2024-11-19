<?php
session_start();
include_once('../config/db_connection.php');

// Check if super admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: super_admin_login.php");
    exit();
}

$sql = "SELECT * FROM users";
$drivers_result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Drivers</title>

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

        /* Navbar Styles */
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
            margin-left: 100px;
            padding: 30px;
            width: calc(100% - 220px);
        }

        .wrapper {
            background-color: var(--white);
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 0.25rem;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table thead th {
            background-color: var(--teal);
            color: var(--white);
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            font-weight: 500;
        }

        .btn-danger:hover {
            opacity: 0.9;
        }

        h4 {
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* Responsive Adjustments */
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

            .table-responsive {
                overflow-x: auto;
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
            <div class="wrapper mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Driver Details</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $drivers_result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['id']) ?></td>
                                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                            <td><?= htmlspecialchars($row['mobile_no']) ?></td>
                                            <td>
                                                <a href="delete_driver.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this driver?');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
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