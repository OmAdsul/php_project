<?php
session_start();
include_once('../config/db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['otp'])) {
    header("Location: user_login.php");
    exit();
}

// Get payment details from session
$challan_id = $_SESSION['challan_id'];
$amount = $_SESSION['amount'];
$card_type = $_SESSION['card_type'];
$cvv = $_SESSION['cvv'];
$expiry_date = $_SESSION['expiry_date'];
$payment_date = date('Y-m-d H:i:s');

// Fetch user email again
$user_email_sql = "SELECT email FROM users WHERE id = ?";
$stmt_user_email = $conn->prepare($user_email_sql);
$stmt_user_email->bind_param("i", $_SESSION['id']);
$stmt_user_email->execute();
$result_user_email = $stmt_user_email->get_result();
$user_email = $result_user_email->fetch_assoc()['email'];

// Store the payment in the database
$sql = "INSERT INTO payments (challan_id, amount, payment_date) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $challan_id, $amount, $payment_date);

if ($stmt->execute()) {
    // Update challan status to 'Paid'
    $sql_update = "UPDATE challans SET status = 'Paid' WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $challan_id);
    $stmt_update->execute();

    // Send confirmation email
    $to = $user_email;
    $subject = "Challan Payment Confirmation";
    $message = "Dear user,\n\nYour payment for Challan ID: $challan_id has been successfully processed. The amount of Rs. $amount has been received. Thank you for resolving your traffic offense.\n\nRegards,\nTraffic Department";
    $headers = "From: 21omadsul@gmail.com";

    mail($to, $subject, $message, $headers);

    // Payment successful
    echo '<script>alert("Payment Successful!");</script>';
    header('location:user_dashboard.php');
} else {
    echo "Payment failed: " . $conn->error;
}

$stmt->close();
$conn->close();
