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

if (file_exists($csvFile)) {
    // Set headers to force download as CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=bookings_export_' . date('Y-m-d') . '.csv');
    header('Content-Length: ' . filesize($csvFile));

    // Output the file directly
    readfile($csvFile);
} else {
    echo "No bookings found to export.";
}
exit;

