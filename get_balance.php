<?php
include 'get_user_data.php';

$wallet_address = $_POST['wallet_address'];
$sql = "SELECT balance FROM users WHERE wallet_address = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $wallet_address);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['success' => true, 'balance' => $row['balance']]);
} else {
    echo json_encode(['success' => false, 'error' => 'Wallet address not found']);
}

$stmt->close();
?>
