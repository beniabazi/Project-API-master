<?php
// Base URL for the API
$baseApiUrl = 'https://mercury.swin.edu.au/ict30017/s104460776/API/';

// Function to fetch data from a specific API endpoint
function fetchData($endpoint) {
    global $baseApiUrl;
    $url = $baseApiUrl . $endpoint;

    // Use authentication credentials from the auth file
    include 'auth_api.php';

    $auth = base64_encode("$apiUsername:$apiPassword");

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $auth));

    $response = curl_exec($ch);

    if ($response === false) {
        die('Error fetching data: ' . curl_error($ch));
    }

    curl_close($ch);

    return json_decode($response, true);
}

// Fetch data for various sections
$users = fetchData('Users');
$residents = fetchData('Residents');
$equipment = fetchData('Equipment');
$familyMembers = fetchData('FamilyMembers');
$individualNeeds = fetchData('IndividualNeeds');
$medications = fetchData('Medications');
$roomBookings = fetchData('RoomBookings');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Portal</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="container">
        <nav class="nav">
            <h1 class="logo"><img src="/img/logo.svg" alt="Dashboard Logo"></h1>
            <ul>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="roster.php">Roster</a></li>
                <li>
                    <a href="#" class="dropbtn">Admin</a>
                    <div id="adminDropdown" class="dropdown-content">
                        <a href="#" data-form-id="user-section">Users</a>
                        <a href="#" data-form-id="residents-section">Residents</a>
                        <a href="#" data-form-id="family-section">Family Members</a>
                        <a href="#" data-form-id="needs-section">Individual Needs</a>
                        <a href="#" data-form-id="medications-section">Medications</a>
                        <a href="#" data-form-id="equipment-section">Equipment</a>
                        <a href="#" data-form-id="room-bookings-section">Room Bookings</a>
                        <a href="#" data-form-id="generate-reports">Generate Reports</a>
                    </div>
                </li>
            </ul>
        </nav>

        <div id="tablet-warning" class="warning hidden">
            <p><strong>Tablet view not supported, please view website on a laptop or desktop.</strong></p>
        </div>
        <div id="mobile-warning" class="warning hidden">
            <p><strong>Mobile view not supported, please view website on a laptop or desktop.</strong></p>
        </div>

        <main class="main-content">
            <header>
                <h1>Welcome to the Admin Portal</h1>
            </header>

            <!-- Users Section -->
            <section id="user-section" class="form active">
                <h2>Manage Users</h2>
                <form id="users-form" action="post_user.php" method="post">
                    <input type="text" name="username" id="username" placeholder="Username" required>
                    <input type="email" name="email" id="email" placeholder="Email Address" required>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <select name="role" id="role" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="User">User</option>
                    </select>
                    <button type="submit">Create User</button>
                </form>
                <table id="users-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['password']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Repeat the structure for Residents, Equipment, Family Members, Individual Needs, Medications, Room Bookings -->

            <!-- Residents Section -->
            <section id="residents-section" class="form">
                <h2>Manage Residents</h2>
                <form id="residents-form" action="post_resident.php" method="post">
                    <input type="text" name="resident-name" id="resident-name" placeholder="Resident Name" required>
                    <input type="text" name="resident-details" id="resident-details" placeholder="Details">
                    <input type="text" name="emergency-contact" id="emergency-contact" placeholder="Emergency Contact">
                    <input type="text" name="care-plan" id="care-plan" placeholder="Care Plan">
                    <button type="submit">Save</button>
                </form>
                <table id="residents-table">
                    <thead>
                        <tr>
                            <th>Resident Name</th>
                            <th>Details</th>
                            <th>Emergency Contact</th>
                            <th>Care Plan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($residents as $resident): ?>
                        <tr>
                            <td><?= htmlspecialchars($resident['residentName']) ?></td>
                            <td><?= htmlspecialchars($resident['details']) ?></td>
                            <td><?= htmlspecialchars($resident['emergencyContact']) ?></td>
                            <td><?= htmlspecialchars($resident['carePlan']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Repeat the section structure for other categories like Equipment, Family Members, etc. -->

            <!-- Generate Reports Section -->
            <section id="generate-reports" class="form">
                <h2>Generate Reports</h2>
                <form action="generate_report.php" method="post">
                    <label for="report-type">Select Report Type:</label>
                    <select id="report-type" name="report-type" required>
                        <option value="equipment">Equipment Report</option>
                        <option value="family-members">Family Members Report</option>
                        <option value="individual-needs">Individual Needs Report</option>
                        <option value="medications">Medications Report</option>
                        <option value="residents">Residents Report</option>
                        <option value="room-bookings">Room Bookings Report</option>
                    </select>
                    <label for="start-date">Start Date:</label>
                    <input type="date" id="start-date" name="start-date" required>
                    <label for="end-date">End Date:</label>
                    <input type="date" id="end-date" name="end-date" required>
                    <button type="submit">Generate Report</button>
                </form>
            </section>
        </main>
    </div>

    <!-- Modal for additional actions or alerts -->
    <div id="modal" class="hidden">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>

    <script src="/js/script.js"></script>
</body>

</html>
