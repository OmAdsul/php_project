<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Offense Reporting and Resolution Platform</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --off-black: #8c8c8c;
            --graystone: #1c1c1c;
            --white: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--graystone);
            color: var(--off-black);
        }

        .navbar {
            background-color: var(--graystone);
        }

        .navbar-brand,
        .nav-link {
            color: var(--off-black) !important;
        }

        .navbar-brand:hover,
        .nav-link:hover {
            color: var(--white) !important;
        }

        .hero-section {
            height: 100vh;
            background: url('./raybay-kG71BXh8KFw-unsplash.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: var(--white);
            position: relative;
            z-index: 1;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--graystone);
            opacity: 0.7;
            z-index: -1;
        }

        .hero-text h1 {
            font-size: 3rem;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .hero-text p {
            font-size: 1.2rem;
            font-weight: 300;
        }

        .btn-custom {
            background-color: var(--off-black);
            color: var(--graystone);
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 1rem;
            margin-top: 20px;
        }

        .btn-custom:hover {
            background-color: var(--white);
            color: var(--graystone);
        }

        .navbar-nav .nav-item:not(:last-child) {
            margin-right: 20px;
        }

        .footer {
            padding: 20px 0;
            text-align: center;
            background: var(--graystone);
            color: var(--off-black);
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .hero-text h1 {
                font-size: 2.5rem;
            }

            .hero-text p {
                font-size: 1rem;
            }

            .btn-custom {
                font-size: 0.9rem;
                padding: 8px 16px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Traffic Offense Platform</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin/super_admin_login.php">Super Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="police/traffic_police_login.php">Traffic Police</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user/user_login.php">Users</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="hero-text">
            <h1>Welcome to the Traffic Offense Reporting Platform</h1>
            <p>Your one-stop solution for reporting and resolving traffic offenses quickly and efficiently.</p>
            <a href="user/user_login.php" class="btn btn-custom">Get Started</a>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 Traffic Offense Reporting Platform. All Rights Reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>