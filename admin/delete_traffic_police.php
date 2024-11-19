<?php
session_start();
include_once('../config/db_connection.php');


// Check if super admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: super_admin_login.php");
    exit();
}

// Delete the traffic police officer 
if (isset($_GET['id'])) {
    $police_id = $_GET['id'];
    $sql = "DELETE FROM traffic_police WHERE police_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $police_id);
    if ($stmt->execute()) {
        header("Location: super_admin_dashboard.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
