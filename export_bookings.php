<?php
session_start();

// Only allow logged-in admins
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("Access denied");
}

// Optional: comment this out while debugging
// error_reporting(0);

// CSV File Path
$csvFile = 'bookings.csv';

if (file_exists($csvFile) && filesize($csvFile) > 0) {
    // Set headers to force download as CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=bookings_export_' . date('Y-m-d') . '.csv');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Read the original CSV
    $file = fopen($csvFile, 'r');

    // Write header row
    fputcsv($output, ['ID', 'Full Name', 'Phone Number', 'City', 'Submission Date']);

    // Skip the original header
    fgetcsv($file);

    // Read all rows
    $rows = [];
    while (($data = fgetcsv($file)) !== FALSE) {
        $rows[] = $data;
    }
    fclose($file);

    // Reverse to show newest first
    $rows = array_reverse($rows);

    // Process and write each row with sequential ID and IST date
    $rowNumber = 1;
    foreach ($rows as $row) {
        $name = isset($row[1]) ? $row[1] : '';
        $phone = isset($row[2]) ? $row[2] : '';
        $city = isset($row[3]) ? $row[3] : '';
        $date = isset($row[4]) ? $row[4] : '';

        // Convert date to IST
        $formattedDate = '';
        if ($date !== '') {
            try {
                $dateObj = new DateTime($date);
                $dateObj->setTimezone(new DateTimeZone('Asia/Kolkata'));
                $formattedDate = $dateObj->format('d M Y, h:i A');
            } catch (Exception $e) {
                $formattedDate = $date;
            }
        }

        // Write row with sequential number and formatted date
        fputcsv($output, [$rowNumber, $name, $phone, $city, $formattedDate]);
        $rowNumber++;
    }

    fclose($output);
} else {
    echo "No bookings found to export.";
}
exit;

