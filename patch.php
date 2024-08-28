<?php
include 'auth_api.php';

function patchData($endpoint, $data) {
    $url = "https://mercury.swin.edu.au/ict30017/s104460776/API/index.php/$endpoint"; // Adjust the URL as needed

    // Initialize cURL
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        getAuthorizationHeader()
    ));

    // Execute cURL request
    $response = curl_exec($ch);

    // Handle cURL errors
    if ($response === false) {
        die('Error patching data: ' . curl_error($ch));
    }

    // Close cURL session
    curl_close($ch);

    // Decode JSON response into associative array
    return json_decode($response, true);
}

// Example usage
$updatedData = [
    'key' => 'updated_value'
];
$response = patchData('your-endpoint', $updatedData);
echo json_encode($response);
?>
