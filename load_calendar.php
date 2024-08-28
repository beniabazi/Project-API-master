<?php
include 'db_connect.php';

$action = $_GET['action'];

if ($action == 'fetch_appointments') {
    $staffId = $_GET['staff_id'];
    $month = $_GET['month'];
    $year = $_GET['year'];

    // Fetch appointments for the specified staff member, month, and year
    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE staff_id = ? AND MONTH(date) = ? AND YEAR(date) = ?");
    $stmt->execute([$staffId, $month, $year]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process appointments into a day-based structure
    $days = [];
    for ($i = 1; $i <= 31; $i++) {
        $days[$i] = ['date' => $i, 'appointments' => []];
    }
    foreach ($appointments as $appointment) {
        $day = (int)date('j', strtotime($appointment['date']));
        $days[$day]['appointments'][] = $appointment['details'];
    }

    echo json_encode(['days' => array_values($days)]);
}

if ($action == 'handle_appointment') {
    $input = json_decode(file_get_contents("php://input"), true);

    $date = $input['date'];
    $time = $input['time'];
    $details = $input['needs'];
    $staffId = $input['staff_id'];

    // Insert new appointment into the database
    $stmt = $pdo->prepare("INSERT INTO appointments (date, time, details, staff_id) VALUES (?, ?, ?, ?)");
    $success = $stmt->execute([$date, $time, $details, $staffId]);

    echo json_encode(['success' => $success]);
}

if ($action == 'delete_appointment') {
    $input = json_decode(file_get_contents("php://input"), true);

    $date = $input['date'];
    $time = $input['time'];
    $staffId = $input['staff_id'];
    $reason = $input['reason'];

    // Delete appointment from the database
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE date = ? AND time = ? AND staff_id = ?");
    $success = $stmt->execute([$date, $time, $staffId]);

    // Log deletion reason if needed
    if ($success) {
        $logStmt = $pdo->prepare("INSERT INTO deletion_logs (date, time, staff_id, reason) VALUES (?, ?, ?, ?)");
        $logStmt->execute([$date, $time, $staffId, $reason]);
    }

    echo json_encode(['success' => $success]);
}
?>
