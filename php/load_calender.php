<?php
include 'db_connect.php';

$staff_id = $_GET['staff_id'];
$month = $_GET['month'];
$year = $_GET['year'];

// Fetch calendar data from the database
$sql = "SELECT * FROM appointments WHERE staff_id = ? AND MONTH(date) = ? AND YEAR(date) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $staff_id, $month, $year);
$stmt->execute();
$result = $stmt->get_result();

$days = [];
while ($row = $result->fetch_assoc()) {
    $date = date('j', strtotime($row['date']));
    if (!isset($days[$date])) {
        $days[$date] = [
            'date' => $date,
            'appointments' => []
        ];
    }
    $days[$date]['appointments'][] = $row['details'];
}

echo json_encode(['days' => array_values($days)]);
?>
