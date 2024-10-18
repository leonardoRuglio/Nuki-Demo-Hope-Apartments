<?php
require_once '../../config/config.php';
// Retrieve POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input data and set dynamic variables
$smartlockId = $data['id'] ?? null;
$name = $data['name'] ?? 'DefaultName';
$allowedFromDate = isset($data['allowedFromDate']) ? new DateTime($data['allowedFromDate']) : new DateTime();
$allowedFromDate = $allowedFromDate->format('Y-m-d\TH:i:s\Z');

$allowedUntilDate = isset($data['allowedUntilDate']) ? new DateTime($data['allowedUntilDate']) : new DateTime();

$addDays = $data['addDays'] ?? 3;
$allowedWeekDays = $data['allowedWeekDays'] ?? 127;
$allowedFromTime = $data['allowedFromTime'] ?? 0;
$allowedUntilTime = $data['allowedUntilTime'] ?? 0;
$enabled = $data['enabled'] ?? true;
$remoteAllowed = $data['remoteAllowed'] ?? true;

if (!$smartlockId) {
    echo json_encode(['error' => true, 'details' => 'Smartlock ID is missing']);
    exit;
}

// Calculate the new date for allowedUntilDate by adding the specified number of days
$newAllowedUntilDate = $allowedUntilDate->modify('+' . $addDays . ' days')->format('Y-m-d\TH:i:s\Z');
// Prepare the data to be sent in the API request
$requestData = array(
    'name' => $name,
    'allowedFromDate' => $allowedFromDate,
    'allowedUntilDate' => $newAllowedUntilDate,
    'allowedWeekDays' => $allowedWeekDays,
    'allowedFromTime' => $allowedFromTime,
    'allowedUntilTime' => $allowedUntilTime,
    'enabled' => $enabled,
    'remoteAllowed' => $remoteAllowed,
    'id' => $smartlockId,
);

// Convert data to JSON format
$jsonData = json_encode($requestData);

// Initialize the cURL session
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . API_TOKEN
));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, '[' . $jsonData . ']');

// Enable verbose logging for cURL
curl_setopt($ch, CURLOPT_VERBOSE, true);

// Execute the request and handle errors
$response = curl_exec($ch);

if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    echo json_encode(['error' => true, 'details' => $error_msg]);
    curl_close($ch);
    exit;
}

// Get HTTP response code and response body
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Log the raw response for debugging
error_log("API Response: " . $response);
error_log("HTTP Status Code: " . $http_code);

// Handle non-200 responses
if ($http_code != 200 && $http_code != 204) {
    echo json_encode([
        'error' => true,
        'details' => 'Failed to extend the date, API returned HTTP status ' . $http_code,
        'response' => $response
    ]);
    exit;
}

// Return success response
echo json_encode(['error' => false, 'details' => 'Updated successfully', 'response' => $response]);
