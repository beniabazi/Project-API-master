<?php
// API authentication credentials
$api_username = "s104460776"; // Replace with your actual API username
$api_password = "Pippathecatisgreat456$"; // Replace with your actual API password

// Function to return the authorization header
function getAuthorizationHeader() {
    global $api_username, $api_password;
    return 'Authorization: Basic ' . base64_encode("$api_username:$api_password");
}
?>
