<?php
// Include the database connection and data management files
include 'db_connect.php';
include 'manage_data.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Portal</title>
    <link rel="stylesheet" href="style/style.css"> <!-- Link to your CSS file -->
</head>

<body>
    <div class="container">
        <nav class="nav">
            <h1 class="logo"><img src="/img/logo.svg" alt="Dashboard Image"></h1>
            <ul>
                <li><a href="#">Logout</a></li>
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

        <div id="tablet-warning">
            <p><strong>Tablet view not supported, please view website on a laptop or desktop.</strong></p>
        </div>
        <div id="mobile-warning">
            <p><strong>Mobile view not supported, please view website on a laptop or desktop.</strong></p>
        </div>

        <main class="main-content">
            <header>
                <h1>Welcome to the Admin Portal</h1>
            </header>

            <!-- Users Section -->
            <section id="user-section" class="form active">
                <h2>Manage Users</h2>
                <form id="user-form" action="process_user_form.php" method="post">
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
                        <!-- Users Data Will Go Here -->
                    </tbody>
                </table>
            </section>

                       <!-- Residents Section -->
                       <section id="residents-section" class="form">
                <h2>Manage Residents</h2>
                <form id="residents-form" action="process_form.php" method="post">
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
                        <!-- Residents Data Will Go Here -->
                    </tbody>
                </table>
            </section>



            <!-- Equipment Section -->
            <section id="equipment-section" class="form">
                <h2>Manage Equipment</h2>
                <form id="equipment-form" action="process_form.php" method="post">
                    <input type="text" name="equipment-name" id="equipment-name" placeholder="Equipment Name" required>
                    <input type="text" name="category" id="category" placeholder="Category">
                    <input type="number" name="quantity" id="quantity" placeholder="Quantity" required>
                    <input type="text" name="condition" id="condition" placeholder="Condition">
                    <button type="submit">Save</button>
                </form>
                <table id="equipment-table">
                    <thead>
                        <tr>
                            <th>Equipment Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Condition</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Equipment Data Will Go Here -->
                    </tbody>
                </table>
            </section>

            <!-- Family Members Section -->
            <section id="family-section" class="form">
                <h2>Manage Family Members</h2>
                <form id="family-form" action="process_form.php" method="post">
                    <input type="text" name="resident-id" id="resident-id" placeholder="Resident ID" required>
                    <input type="text" name="family-member-name" id="family-member-name" placeholder="Family Member Name" required>
                    <input type="text" name="relationship" id="relationship" placeholder="Relationship">
                    <input type="text" name="contact-details" id="contact-details" placeholder="Contact Details">
                    <button type="submit">Save</button>
                </form>
                <table id="family-table">
                    <thead>
                        <tr>
                            <th>Resident ID</th>
                            <th>Family Member Name</th>
                            <th>Relationship</th>
                            <th>Contact Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Family Member Data Will Go Here -->
                    </tbody>
                </table>
            </section>

            <!-- Individual Needs Section -->
            <section id="needs-section" class="form">
                <h2>Manage Individual Needs</h2>
                <form id="needs-form" action="process_form.php" method="post">
                    <input type="text" name="resident-id" id="resident-id" placeholder="Resident ID" required>
                    <input type="text" name="need-description" id="need-description" placeholder="Need Description">
                    <input type="text" name="priority" id="priority" placeholder="Priority Level">
                    <input type="text" name="service-provider" id="service-provider" placeholder="Service Provider">
                    <button type="submit">Save</button>
                </form>
                <table id="needs-table">
                    <thead>
                        <tr>
                            <th>Resident ID</th>
                            <th>Need Description</th>
                            <th>Priority Level</th>
                            <th>Service Provider</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Individual Needs Data Will Go Here -->
                    </tbody>
                </table>
            </section>

            <!-- Medications Section -->
            <section id="medications-section" class="form">
                <h2>Manage Medications</h2>
                <form id="medications-form" action="process_form.php" method="post">
                    <input type="text" name="resident-id" id="resident-id" placeholder="Resident ID" required>
                    <input type="text" name="medication-name" id="medication-name" placeholder="Medication Name" required>
                    <input type="text" name="dosage" id="dosage" placeholder="Dosage">
                    <input type="text" name="frequency" id="frequency" placeholder="Frequency">
                    <input type="text" name="prescribing-doctor" id="prescribing-doctor" placeholder="Prescribing Doctor">
                    <button type="submit">Save</button>
                </form>
                <table id="medications-table">
                    <thead>
                        <tr>
                            <th>Resident ID</th>
                            <th>Medication Name</th>
                            <th>Dosage</th>
                            <th>Frequency</th>
                            <th>Prescribing Doctor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Medications Data Will Go Here -->
                    </tbody>
                </table>
            </section>

 

            <!-- Room Bookings Section -->
            <section id="room-bookings-section" class="form">
                <h2>Manage Room Bookings</h2>
                <form id="room-bookings-form" action="process_form.php" method="post">
                    <input type="text" name="resident-id" id="resident-id" placeholder="Resident ID" required>
                    <input type="text" name="room-number" id="room-number" placeholder="Room Number" required>
                    <input type="date" name="check-in-date" id="check-in-date" placeholder="Check-in Date" required>
                    <input type="date" name="check-out-date" id="check-out-date" placeholder="Check-out Date" required>
                    <button type="submit">Save</button>
                </form>
                <table id="room-bookings-table">
                    <thead>
                        <tr>
                            <th>Resident ID</th>
                            <th>Room Number</th>
                            <th>Check-in Date</th>
                            <th>Check-out Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Room Bookings Data Will Go Here -->
                    </tbody>
                </table>
            </section>

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
