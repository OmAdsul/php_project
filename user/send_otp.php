<?php
session_start();
include_once('../config/db_connection.php');

// Get the payment details from the form
$challan_id = $_POST['challan_id'];
$amount = $_POST['amount'];
$mobile_number = $_POST['mobile_number'];
$card_type = $_POST['card_type'];
$cvv = $_POST['cvv'];
$expiry_date = $_POST['expiry_date'];

// Fetch user's email from the database
$sql_email = "SELECT email FROM users WHERE mobile_no = ?";
$stmt_email = $conn->prepare($sql_email);
$stmt_email->bind_param("s", $mobile_number);
$stmt_email->execute();
$result_email = $stmt_email->get_result();
$user_email = $result_email->fetch_assoc()['email'];

// Generate a random 6-digit OTP
$otp = rand(100000, 999999);

// Store OTP and payment details in session for later verification
$_SESSION['otp'] = $otp;
$_SESSION['challan_id'] = $challan_id;
$_SESSION['amount'] = $amount;
$_SESSION['card_type'] = $card_type;
$_SESSION['cvv'] = $cvv;
$_SESSION['expiry_date'] = $expiry_date;

// Send OTP to the user's email
$to = $user_email;
$subject = "Your OTP for Payment Verification";
$message = "Dear user,\n\nYour OTP for the payment verification is: $otp\nPlease enter this OTP to confirm your payment.\n\nThank you.";
$headers = "From: 21omadsul@gmail.com";

mail($to, $subject, $message, $headers);

// Redirect to the OTP verification page
header("Location: verify_otp.php");
exit();
