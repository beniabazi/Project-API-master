<?php
// Include the database connection and data management files
include 'db_connect.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Get table name from query parameters and sanitize it
$tableName = isset($_GET['table']) ? sanitize_input($_GET['table']) : '';

if (!$tableName) {
    echo json_encode([]);
    exit();
}

// Define valid table names for security
$validTables = [
    'users',
    'residents',
    'equipment',
    'family_members',
    'individual_needs',
    'medications',
    'room_bookings'
];

if (!in_array($tableName, $validTables)) {
    echo json_encode([]);
    exit();
}

// Prepare the SQL query based on the table name
$sql = "SELECT * FROM $tableName";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

// Close connection
$conn->close();
?>
