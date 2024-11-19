<?php
session_start();

// Destroy the session.
session_destroy();

// Redirect to the traffic police login page.
header("Location: traffic_police_login.php");
exit();
