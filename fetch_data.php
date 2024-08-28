<?php
// Include the database connection file
include 'db_connect.php';

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Get table name from query parameters and sanitize it
$tableName = isset($_GET['table']) ? sanitize_input($_GET['table']) : '';

if (empty($tableName)) {
    echo json_encode(['error' => 'No table specified']);
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
    echo json_encode(['error' => 'Invalid table name']);
    exit();
}

try {
    // Prepare the SQL query based on the table name
    $sql = "SELECT * FROM $tableName";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Send response
    echo json_encode($data);

} catch (PDOException $e) {
    // Handle errors
    echo json_encode(['error' => 'Database query failed: ' . $e->getMessage()]);
}
?>
