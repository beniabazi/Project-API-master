<?php
include 'db_connect.php';

// Function to create or update data
function saveData($table, $data) {
    global $conn;

    $columns = implode(", ", array_keys($data));
    $values = implode("', '", array_values($data));

    $query = "INSERT INTO $table ($columns) VALUES ('$values') 
              ON DUPLICATE KEY UPDATE ";

    foreach ($data as $key => $value) {
        $query .= "$key = '$value', ";
    }

    $query = rtrim($query, ', ') . ";";

    if ($conn->query($query) === TRUE) {
        return "Record updated successfully";
    } else {
        return "Error: " . $query . "<br>" . $conn->error;
    }
}

// Function to fetch data from the database
function fetchData($table) {
    global $conn;

    $query = "SELECT * FROM $table";
    $result = $conn->query($query);

    $data = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Function to delete data
function deleteData($table, $idColumn, $id) {
    global $conn;

    $query = "DELETE FROM $table WHERE $idColumn = '$id'";
    if ($conn->query($query) === TRUE) {
        return "Record deleted successfully";
    } else {
        return "Error deleting record: " . $conn->error;
    }
}

// Handle incoming POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $table = $_POST['table'];
    $data = $_POST['data'];

    switch($action) {
        case 'save':
            $response = saveData($table, $data);
            break;
        case 'delete':
            $idColumn = $_POST['idColumn'];
            $id = $_POST['id'];
            $response = deleteData($table, $idColumn, $id);
            break;
        default:
            $response = "Invalid action";
    }

    echo json_encode(['response' => $response]);
}

// Handle incoming GET requests
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $table = $_GET['table'];
    $data = fetchData($table);
    echo json_encode($data);
}

?>
