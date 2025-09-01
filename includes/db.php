<?php
// Database configuration
$host = 'localhost';
$dbname = 'goodfix_complaints';
$username = 'root';
$password = 'root';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
