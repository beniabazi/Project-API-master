<?php
// Include the database connection file
include 'db_connect.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize inputs
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Determine which form was submitted
    if (isset($_POST['username'])) {
        // User form submission
        $username = sanitize_input($_POST['username']);
        $email = sanitize_input($_POST['email']);
        $password = sanitize_input($_POST['password']);
        $role = sanitize_input($_POST['role']);

        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, password_hash($password, PASSWORD_DEFAULT), $role);

    } elseif (isset($_POST['resident-name'])) {
        // Residents form submission
        $resident_name = sanitize_input($_POST['resident-name']);
        $resident_details = sanitize_input($_POST['resident-details']);
        $emergency_contact = sanitize_input($_POST['emergency-contact']);
        $care_plan = sanitize_input($_POST['care-plan']);

        $sql = "INSERT INTO residents (resident_name, details, emergency_contact, care_plan) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $resident_name, $resident_details, $emergency_contact, $care_plan);

    } elseif (isset($_POST['equipment-name'])) {
        // Equipment form submission
        $equipment_name = sanitize_input($_POST['equipment-name']);
        $category = sanitize_input($_POST['category']);
        $quantity = (int) sanitize_input($_POST['quantity']); // Ensure quantity is treated as an integer
        $condition = sanitize_input($_POST['condition']);

        $sql = "INSERT INTO equipment (equipment_name, category, quantity, condition) VALUES (?, ?, ?, ?)";
        $stmt->prepare($sql);
        $stmt->bind_param("ssis", $equipment_name, $category, $quantity, $condition);

    } elseif (isset($_POST['family-member-name'])) {
        // Family Members form submission
        $resident_id = sanitize_input($_POST['resident-id']);
        $family_member_name = sanitize_input($_POST['family-member-name']);
        $relationship = sanitize_input($_POST['relationship']);
        $contact_details = sanitize_input($_POST['contact-details']);

        $sql = "INSERT INTO family_members (resident_id, family_member_name, relationship, contact_details) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $resident_id, $family_member_name, $relationship, $contact_details);

    } elseif (isset($_POST['need-description'])) {
        // Individual Needs form submission
        $resident_id = sanitize_input($_POST['resident-id']);
        $need_description = sanitize_input($_POST['need-description']);
        $priority = sanitize_input($_POST['priority']);
        $service_provider = sanitize_input($_POST['service-provider']);

        $sql = "INSERT INTO individual_needs (resident_id, need_description, priority, service_provider) VALUES (?, ?, ?, ?)";
        $stmt->prepare($sql);
        $stmt->bind_param("ssss", $resident_id, $need_description, $priority, $service_provider);

    } elseif (isset($_POST['medication-name'])) {
        // Medications form submission
        $resident_id = sanitize_input($_POST['resident-id']);
        $medication_name = sanitize_input($_POST['medication-name']);
        $dosage = sanitize_input($_POST['dosage']);
        $frequency = sanitize_input($_POST['frequency']);
        $prescribing_doctor = sanitize_input($_POST['prescribing-doctor']);

        $sql = "INSERT INTO medications (resident_id, medication_name, dosage, frequency, prescribing_doctor) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $resident_id, $medication_name, $dosage, $frequency, $prescribing_doctor);

    } elseif (isset($_POST['room-number'])) {
        // Room Bookings form submission
        $resident_id = sanitize_input($_POST['resident-id']);
        $room_number = sanitize_input($_POST['room-number']);
        $check_in_date = sanitize_input($_POST['check-in-date']);
        $check_out_date = sanitize_input($_POST['check-out-date']);

        $sql = "INSERT INTO room_bookings (resident_id, room_number, check_in_date, check_out_date) VALUES (?, ?, ?, ?)";
        $stmt->prepare($sql);
        $stmt->bind_param("ssss", $resident_id, $room_number, $check_in_date, $check_out_date);

    } else {
        echo "Invalid form submission.";
        exit();
    }

    // Execute the query
    if ($stmt->execute()) {
        echo "Record successfully inserted.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
