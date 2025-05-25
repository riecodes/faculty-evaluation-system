<?php
// export_db.php
// Exports the latest database schema and data as SQL

// Database credentials
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'faculty_evaluation_system'; // Change to your DB name

// Set headers to force download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="faculty_evaluation_system_export_' . date('Ymd_His') . '.sql"');

// Use mysqldump if available
$mysqldump = "mysqldump";
$command = "$mysqldump --user=$user --password=$password --host=$host $dbname";

// Try to execute mysqldump
$process = popen($command, 'r');
if (is_resource($process)) {
    while (!feof($process)) {
        echo fread($process, 4096);
    }
    pclose($process);
    exit;
} else {
    // Fallback: PHP-based export (schema only, no data)
    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    $tables = [];
    $result = $conn->query('SHOW TABLES');
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
    foreach ($tables as $table) {
        $create = $conn->query("SHOW CREATE TABLE `$table`")->fetch_array();
        echo "\n-- Table structure for `$table`\n";
        echo $create[1] . ";\n";
        // Optionally, export data as INSERTs (not implemented here for brevity)
    }
    $conn->close();
    exit;
} 