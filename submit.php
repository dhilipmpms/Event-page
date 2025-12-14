<?php
header('Content-Type: application/json');

// 1. Process Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Suppress warnings to ensure clean JSON output
    error_reporting(E_ERROR | E_PARSE);

    // Sanitize Inputs
    $full_name = isset($_POST['name']) ? strip_tags(trim($_POST['name'])) : '';
    $phone = isset($_POST['mobile']) ? strip_tags(trim($_POST['mobile'])) : '';
    $city = isset($_POST['city']) ? strip_tags(trim($_POST['city'])) : '';



    if (empty($full_name) || empty($phone) || empty($city)) {
        $response['success'] = false;
        $response['message'] = "Please fill in all required fields. Received: Name=$full_name, Phone=$phone, City=$city";
        echo json_encode($response);
        exit();
    }

    // Validate phone number (must be exactly 10 digits)
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $response['success'] = false;
        $response['message'] = "Invalid phone number. Please enter exactly 10 digits.";
        echo json_encode($response);
        exit();
    }

    // CSV File Path
    $csvFile = 'bookings.csv';

    // Generate unique ID and timestamp
    $id = uniqid(); // Or use a simpler counter if preferred, but uniqid is safer without a database
    $timestamp = date("Y-m-d H:i:s");

    // Prepare data array
    $data = [$id, $full_name, $phone, $city, $timestamp];

    // Append to CSV
    $fp = fopen($csvFile, 'a');
    if ($fp) {
        if (flock($fp, LOCK_EX)) { // Acquire exclusive lock
            // Check if file is empty to add headers (optional, but good for direct excel opening)
            if (filesize($csvFile) == 0) {
                fputcsv($fp, ['ID', 'Full Name', 'Phone', 'City', 'Submission Date']);
            }
            fputcsv($fp, $data);
            flock($fp, LOCK_UN); // Release lock

            $response['success'] = true;
            $response['message'] = "Booking submitted successfully!";



        } else {
            $response['message'] = "Error: Could not lock database file.";
        }
        fclose($fp);
    } else {
        $response['message'] = "Error: Could not open database file.";
    }

} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>