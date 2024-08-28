<?php
// Include the database connection
include 'db_config.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// Perform a simple query to test
$sql = "SELECT * FROM ResidentProfiles LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of the first row
    while($row = $result->fetch_assoc()) {
        echo "<pre>" . print_r($row, true) . "</pre>";
    }
} else {
    echo "0 results";
}

// Close the database connection
$conn->close();
?>
 