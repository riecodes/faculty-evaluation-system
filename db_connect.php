<?php
// ALWAYS COMMENT OUT THE ONE YOU ARE NOT USING

// Parameters Guide
// $host = 'localhost';
// $user = 'root';
// $password = '';
// $dbname = 'evaluation_db_main';

// Production (Hostinger)
$conn = new mysqli('localhost', 'u520508530_evaluation_db', '5]bbUx/Zs', 'u520508530_evaluation_db');
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Locally (uncomment when running on your local machine)
// $conn = new mysqli('localhost', 'root', '', 'evaluation_db_main');
// if ($conn->connect_error) {
//     die('Database connection failed: ' . $conn->connect_error);
// }
