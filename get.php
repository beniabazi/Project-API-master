<?php
include 'auth_api.php';

function fetchData($endpoint) {
    $url = "https://mercury.swin.edu.au/ict30017/s104460776/API/index.php/$endpoint"; // Adjust the URL as needed

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(getAuthorizationHeader()));

    // Execute cURL request
    $response = curl_exec($ch);

    // Handle cURL errors
    if ($response === false) {
        die('Error fetching data: ' . curl_error($ch));
    }

    // Close cURL session
    curl_close($ch);

    // Decode JSON response into associative array
    return json_decode($response, true);
}

// Example usage
$data = fetchData('your-endpoint');
echo json_encode($data);
?>
