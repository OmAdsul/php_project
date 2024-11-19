<?php
session_start();
include_once('../config/db_connection.php');


// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: user_login.php");
    exit();
}

// Get form data
$challan_id = $_POST['challan_id'];
$amount = $_POST['amount'];
$mobile_number = $_POST['mobile_number'];
$card_type = $_POST['card_type'];
$cvv = $_POST['cvv'];
$expiry_date = $_POST['expiry_date'];
$payment_date = date('Y-m-d H:i:s');

// Store the payment in the database
$sql = "INSERT INTO payments (challan_id, amount, payment_date) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $challan_id, $amount, $payment_date);

if ($stmt->execute()) {
    // Update challan status to Paid
    $sql_update = "UPDATE challans SET status = 'Paid' WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $challan_id);
    $stmt_update->execute();

    echo "Payment successful!";

?>
    <script>
        alert('Payment Successful')
    </script>

<?php
    header('location:user_dashboard.php');
} else {
    echo "Payment failed: " . $conn->error;
}

$stmt->close();
$conn->close();
?>