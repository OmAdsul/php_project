<?php
session_start();
include_once('../config/db_connection.php');

// Check if super admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: super_admin_login.php");
    exit();
}

// Check if driver ID 
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $driver_id = $_GET['id'];

    $conn->begin_transaction();

    try {
        // Delete the user from the `users` table
        $deleteUserSql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($deleteUserSql);
        if (!$stmt) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        $stmt->bind_param("i", $driver_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        $stmt->close();

        $conn->commit();
        $_SESSION['success_message'] = "Driver and all related records have been successfully deleted.";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_message'] = "Failed to delete driver: " . $e->getMessage();
    }

    $conn->close();
} else {
    $_SESSION['error_message'] = "Invalid request.";
}

header("Location: view_driver.php");
exit();
