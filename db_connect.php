<?php
// ALWAYS COMMENT OUT THE ONE YOU ARE NOT USING

// Parameters Guide
// $host = 'localhost';
// $user = 'root';
// $password = '';
// $dbname = 'evaluation_db_main';

// Locally
$conn = new mysqli('localhost', 'root', '', 'evaluation_db_main') or die("Could not connect to mysql" . mysqli_error($con));
// Production
$conn = new mysqli('127.0.0.1:3306', 'u520508530_evaluation_db', '5]bbUx/Zs', 'u520508530_evaluation_db') or die("Could not connect to mysql" . mysqli_error($con));$conn = new mysqli('localhost', 'root', '', 'evaluation_db_main') or die("Could not connect to mysql" . mysqli_error($con));
