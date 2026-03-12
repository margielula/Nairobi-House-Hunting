<?php
session_start();
$title = "View Bookings - Admin Dashboard";
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

// Handle delete booking action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM bookings WHERE id = $id");
    header("Location: view_bookings.php");
    exit;
}

// Handle status update
if (isset($_GET['confirm'])) {
    $id = intval($_GET['confirm']);
    $conn->query("UPDATE bookings SET status='confirmed' WHERE id = $id");
    header("Location: view_bookings.php");
    exit;
}

if (isset($_GET['cancel'])) {
    $id = intval($_GET['cancel']);
    $conn->query("UPDATE bookings SET status='cancelled' WHERE id = $id");
    header("Location: view_bookings.php");
    exit;
}

// Get all bookings with user and house info
$bookings_result = $conn->query("SELECT b.id, b.booking_date, b.status, b.contact, u.username, u.email, h.location, h.house_type, h.price FROM bookings b JOIN users u ON b.user_id = u.id JOIN houses h ON b.house_id = h.id ORDER BY b.id DESC");
$bookings = [];
while ($row = $bookings_result->fetch_assoc()) {
    $bookings[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="index.html code/style.css">
    <style>
        .manage-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-header h1 { color: #2c3e50; }
        .back-btn { background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #e67e22; color: white; }
        tr:hover { background: #f5f5f5; }
        
        .btn { padding: 5px 12px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; text-decoration: none; margin-right: 5px; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-confirm { background: #27ae60; color: white; }
        .btn-cancel { background: #f39c12; color: white; }
        
        .status { padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; }
        .status.pending { background: #f39c12; color: white; }
        .status.confirmed { background: #27ae60; color: white; }
        .status.cancelled { background: #e74c3c; color: white; }
        
        .empty-state { 
            text-align: center; 
            padding: 60px 20px; 
            color: #7f8c8d; 
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .booking-count { 
            background: #e67e22; 
            color: white; 
            padding: 5px 15px; 
            border-radius: 20px; 
            font-size: 14px; 
        }
    </style>
</head>
<body>
    <div class="manage-container">
        <div class="page-header">
            <h1>📅 View House Bookings</h1>
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>
        
        <div style="margin-bottom: 20px;">
            <h3>🏠 House Bookings <span class="booking-count"><?php echo count($bookings); ?></span></h3>
        </div>
        
        <?php if (count($bookings) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>House</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo $booking['id']; ?></td>
                            <td><?php echo htmlspecialchars($booking['username']); ?></td>
                            <td><?php echo htmlspecialchars($booking['email']); ?></td>
                            <td><?php echo htmlspecialchars($booking['contact']); ?></td>
                            <td><?php echo htmlspecialchars($booking['house_type']); ?></td>
                            <td><?php echo htmlspecialchars($booking['location']); ?></td>
                            <td>Ksh <?php echo number_format($booking['price']); ?></td>
                            <td><span class="status <?php echo $booking['status']; ?>"><?php echo ucfirst($booking['status']); ?></span></td>
                            <td><?php echo date('M d, Y H:i', strtotime($booking['booking_date'])); ?></td>
                            <td>
                                <?php if ($booking['status'] == 'pending'): ?>
                                    <a href="?confirm=<?php echo $booking['id']; ?>" class="btn btn-confirm">Confirm</a>
                                    <a href="?cancel=<?php echo $booking['id']; ?>" class="btn btn-cancel">Cancel</a>
                                <?php endif; ?>
                                <a href="?delete=<?php echo $booking['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <p style="font-size: 48px; margin-bottom: 20px;">📅</p>
                <p style="font-size: 18px;">No bookings yet.</p>
                <p>House bookings will appear here.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
