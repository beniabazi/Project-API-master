<?php
// Include the database connection
include 'db_connect.php';

// Function to fetch appointments for a given staff member, month, and year
function fetchAppointments($staff_id, $month, $year) {
    global $conn;
    $appointments = [];
    try {
        $sql = "SELECT date, time, needs FROM appointments WHERE staff_id = ? AND MONTH(date) = ? AND YEAR(date) = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

        $stmt->bind_param("iii", $staff_id, $month, $year);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $dateKey = date('Y-m-d', strtotime($row['date']));
            $appointments[$dateKey][] = "Appointment at {$row['time']}: {$row['needs']}";
        }
        $stmt->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        $appointments = ['error' => 'Failed to fetch appointments'];
    }
    return $appointments;
}

// Function to handle the scheduling of appointments
function handleAppointment($date, $time, $needs, $staff_id) {
    global $conn;
    try {
        $sql = "SELECT * FROM appointments WHERE staff_id = ? AND date = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

        $stmt->bind_param("is", $staff_id, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $sql = "UPDATE appointments SET time = ?, needs = ? WHERE staff_id = ? AND date = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

            $stmt->bind_param("ssis", $time, $needs, $staff_id, $date);
        } else {
            $sql = "INSERT INTO appointments (staff_id, date, time, needs) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

            $stmt->bind_param("isss", $staff_id, $date, $time, $needs);
        }
        $stmt->execute();
        echo json_encode(['success' => true]);
        $stmt->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// Function to delete an appointment
function deleteAppointment($staff_id, $date) {
    global $conn;
    try {
        $sql = "DELETE FROM appointments WHERE staff_id = ? AND date = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

        $stmt->bind_param("is", $staff_id, $date);
        $stmt->execute();
        echo json_encode(['success' => true]);
        $stmt->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// Function to fetch data from a specific table
function fetchData($tableName) {
    global $conn;
    $allowedTables = ['equipment', 'family_members', 'individual_needs', 'medications', 'residents', 'room_bookings'];
    if (!in_array($tableName, $allowedTables)) {
        return ['error' => 'Invalid table name'];
    }
    $data = [];
    try {
        $sql = "SELECT * FROM $tableName";
        $result = $conn->query($sql);
        if ($result === false) throw new Exception("Query failed: " . $conn->error);

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        $data = ['error' => 'Failed to fetch data'];
    }
    return $data;
}

// Function to generate reports based on type and date range
function generateReport($report_type, $start_date, $end_date) {
    global $conn;
    $data = [];
    try {
        $date_format = '/^\d{4}-\d{2}-\d{2}$/';
        if (!preg_match($date_format, $start_date) || !preg_match($date_format, $end_date)) {
            throw new Exception("Invalid date format. Use YYYY-MM-DD.");
        }

        $allowedTypes = ['resident', 'equipment', 'family_members', 'individual_needs', 'medications', 'room_bookings'];
        if (!in_array($report_type, $allowedTypes)) {
            throw new Exception("Invalid report type selected.");
        }

        $query = "SELECT * FROM $report_type WHERE created_at BETWEEN ? AND ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        $data = ['error' => $e->getMessage()];
    }
    return $data;
}

// Main logic based on requested action
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'fetch_appointments':
            if (isset($_GET['staff_id'], $_GET['month'], $_GET['year'])) {
                $staff_id = intval($_GET['staff_id']);
                $month = intval($_GET['month']);
                $year = intval($_GET['year']);
                
                $appointments = fetchAppointments($staff_id, $month, $year);
                
                $days = [];
                foreach ($appointments as $date => $appointmentsOnDate) {
                    $days[] = [
                        'date' => $date,
                        'appointments' => $appointmentsOnDate
                    ];
                }
                
                echo json_encode(['days' => $days]);
            } else {
                echo json_encode(['error' => 'Missing parameters']);
            }
            break;

        case 'handle_appointment':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['date'], $data['time'], $data['needs'], $data['staff_id'])) {
                handleAppointment($data['date'], $data['time'], $data['needs'], $data['staff_id']);
            } else {
                echo json_encode(['error' => 'Missing parameters']);
            }
            break;
            
        case 'delete_appointment':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['staff_id'], $data['date'])) {
                deleteAppointment($data['staff_id'], $data['date']);
            } else {
                echo json_encode(['error' => 'Missing parameters']);
            }
            break;
            
        case 'fetch_data':
            if (isset($_GET['table'])) {
                echo json_encode(fetchData($_GET['table']));
            } else {
                echo json_encode(['error' => 'No table specified']);
            }
            break;

        case 'generate_report':
            if (isset($_POST['report-type'], $_POST['start-date'], $_POST['end-date'])) {
                $result = generateReport($_POST['report-type'], $_POST['start-date'], $_POST['end-date']);
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'Missing parameters']);
            }
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'No action specified']);
}

$conn->close();
?>
