<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$DB_SERVER = 'feenix-mariadb-web.swin.edu.au'; // or the correct hostname
$DB_USERNAME = 's103332735'; // Your MySQL username
$DB_PASSWORD = '!4srz7eGr!'; // Your MySQL password
$DB_DATABASE = 's103332735_db'; // Your MySQL database name

// Create connection
$conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}