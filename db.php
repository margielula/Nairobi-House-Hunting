<?php
// ============================
// Nairobi House Hunting DB Config
// ============================

// MySQL connection settings
$host = "127.0.0.1";                     // MySQL host
$user = "root";                           // MySQL username
$password = "";                           // MySQL password (empty if none)
$database = "nairobi_house_hunting";     // Your database name
$port = 3307;                             // MySQL port

// Create connection
$conn = new mysqli($host, $user, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set character set to UTF-8
$conn->set_charset("utf8mb4");

// ============================
// Usage example (for testing)
// ============================
// $result = $conn->query("SELECT * FROM houses");
// while($row = $result->fetch_assoc()) {
//     echo $row['title'] . " - " . $row['price'] . "<br>";
// }
?>
