<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

if (isset($_POST['house_id'])) {
    $house_id = intval($_POST['house_id']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $username = $_SESSION['username'];

    // Get user_id
    $user_result = $conn->query("SELECT id FROM users WHERE username='$username'");
    if ($user_result && $user = $user_result->fetch_assoc()) {
        $user_id = $user['id'];

        // Insert booking
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, house_id, contact) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $house_id, $contact);
        if ($stmt->execute()) {
            header("Location: index.php?booking=success");
            exit;
        } else {
            echo "Booking failed.";
        }
        $stmt->close();
    } else {
        echo "User not found.";
    }
} else {
    echo "Invalid house.";
}

$conn->close();
?>
