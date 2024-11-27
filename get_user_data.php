<?php
// Database connection details
$servername = "localhost";
$username = "ulbigtja_botuser";
$password = "Gr@t1+Qk&st3";
$dbname = "ulbigtja_botdb"; // The name of your database

// Create connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Extract the userId from the POST data
$userId = $data['userId'];
$newBalance = isset($data['newBalance']) ? $data['newBalance'] : null;

// Initialize response data
$response = [
    'balance' => 0,
    'daily_tasks' => []
];

// Check if the user exists in the database
$sql = "SELECT balance, daily_tasks FROM users WHERE telegram_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If user exists, fetch the balance and tasks
    $row = $result->fetch_assoc();
    $response['balance'] = $row['balance'];
    $response['daily_tasks'] = json_decode($row['daily_tasks'], true);
} else {
    // If user doesn't exist, insert a new record with default values
    $defaultTasks = json_encode(["Task 1", "Task 2", "Task 3"]);
    $insertSql = "INSERT INTO users (telegram_id, balance, daily_tasks) VALUES (?, 0, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("ss", $userId, $defaultTasks);
    $insertStmt->execute();

    // Set the default values in the response
    $response['daily_tasks'] = json_decode($defaultTasks, true);
}

// If a new balance is provided (indicating a task completion or reward claim), update the balance
if ($newBalance !== null) {
    $updateSql = "UPDATE users SET balance = ? WHERE telegram_id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("is", $newBalance, $userId);
    $updateStmt->execute();
    
    // Update the response balance to reflect the new balance
    $response['balance'] = $newBalance;
    $updateStmt->close();
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the response as JSON
echo json_encode($response);
?>