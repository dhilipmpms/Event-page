<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Maansarovar Events</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-section {
            padding: calc(var(--header-height) + 2rem) 0 4rem;
            background-color: hsl(var(--background));
            min-height: 100vh;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table-container {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            overflow-x: auto;
            border: 1px solid hsl(var(--border));
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        th,
        td {
            text-align: left;
            padding: 1rem;
            border-bottom: 1px solid hsl(var(--border));
        }

        th {
            background-color: hsl(var(--secondary) / 0.5);
            font-weight: 600;
            color: hsl(var(--foreground));
        }

        tr:hover {
            background-color: hsl(var(--secondary) / 0.2);
        }
    </style>
</head>

<body>

    <header class="header scrolled">
        <div class="container header-container">
            <a href="index.html" class="logo">MAANSAROVAR <span style="font-size: 0.7em; opacity: 0.7;">ADMIN</span></a>
            <nav class="desktop-nav">
                <a href="index.html" class="nav-link">View Site</a>
                <a href="logout.php" class="nav-link" style="color: #ef4444;">Logout</a>
            </nav>
        </div>
    </header>

    <main class="admin-section">
        <div class="container">
            <div class="admin-header">
                <div>
                    <h1 class="section-title">Bookings Dashboard</h1>
                    <p class="section-subtitle">Manage and view all event registrations</p>
                </div>
                <a href="export_bookings.php" class="btn btn-primary">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="margin-right: 0.5rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export to Excel (CSV)
                </a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Phone Number</th>
                            <th>City</th>
                            <th>Submission Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $csvFile = 'bookings.csv';

                        if (file_exists($csvFile)) {
                            $file = fopen($csvFile, 'r');

                            // Check for empty file
                            if (filesize($csvFile) > 0) {
                                // Skip header row
                                $headers = fgetcsv($file);

                                $rows = [];
                                while (($data = fgetcsv($file)) !== FALSE) {
                                    // Store rows to reverse them later (LIFO)
                                    $rows[] = $data;
                                }
                                fclose($file);

                                // Reverse array to show newest first
                                $rows = array_reverse($rows);

                                if (count($rows) > 0) {
                                    $rowNumber = 1; // Sequential counter
                                    foreach ($rows as $row) {
                                        // Ensure row has expected number of columns to avoid offset errors
                                        // Format: ID, Full Name, Phone, City, Timestamp
                                        $id = isset($row[0]) ? $row[0] : '';
                                        $name = isset($row[1]) ? $row[1] : '';
                                        $phone = isset($row[2]) ? $row[2] : '';
                                        $city = isset($row[3]) ? $row[3] : '';
                                        $date = isset($row[4]) ? $row[4] : '';

                                        // Convert date to IST (Indian Standard Time)
                                        $formattedDate = '';
                                        if ($date !== '') {
                                            try {
                                                $dateObj = new DateTime($date);
                                                $dateObj->setTimezone(new DateTimeZone('Asia/Kolkata'));
                                                $formattedDate = $dateObj->format('d M Y, h:i A');
                                            } catch (Exception $e) {
                                                $formattedDate = htmlspecialchars($date);
                                            }
                                        }

                                        echo "<tr>";
                                        echo "<td>" . $rowNumber . "</td>"; // Sequential number instead of unique ID
                                        echo "<td class='fw-medium'>" . htmlspecialchars($name) . "</td>";
                                        echo "<td>" . htmlspecialchars($phone) . "</td>";
                                        echo "<td>" . ($city !== "" ? htmlspecialchars($city) : "<em>Empty</em>") . "</td>";
                                        echo "<td class='text-muted'>" . $formattedDate . "</td>";
                                        echo "</tr>";

                                        $rowNumber++; // Increment counter
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center p-4'>No bookings found</td></tr>";
                                }

                            } else {
                                echo "<tr><td colspan='5' class='text-center p-4'>No bookings found</td></tr>";
                                fclose($file);
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center p-4'>No bookings found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>

</html>