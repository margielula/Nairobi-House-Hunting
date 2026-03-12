<?php
session_start();
$title = "Admin Dashboard - Nairobi House Hunting";
include 'db.php';

// Check if user is logged in (simple check)
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

// Check if user is Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: index.php");
    exit;
}

// Get statistics
$houses_count = 0;
$users_count = 0;
$contacts_count = 0;
$bookings_count = 0;

$houses_result = $conn->query("SELECT COUNT(*) as count FROM houses");
if ($houses_result && $row = $houses_result->fetch_assoc()) {
    $houses_count = $row['count'];
}

$users_result = $conn->query("SELECT COUNT(*) as count FROM users");
if ($users_result && $row = $users_result->fetch_assoc()) {
    $users_count = $row['count'];
}

$contacts_result = $conn->query("SELECT COUNT(*) as count FROM contacts");
if ($contacts_result && $row = $contacts_result->fetch_assoc()) {
    $contacts_count = $row['count'];
}

$bookings_result = $conn->query("SELECT COUNT(*) as count FROM bookings");
if ($bookings_result && $row = $bookings_result->fetch_assoc()) {
    $bookings_count = $row['count'];
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="index.html code/style.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .dashboard-header h1 {
            color: #2c3e50;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            color: #7f8c8d;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .stat-card .number {
            font-size: 48px;
            font-weight: bold;
            color: #3498db;
        }
        .stat-card.houses .number { color: #27ae60; }
        .stat-card.users .number { color: #9b59b6; }
        .stat-card.contacts .number { color: #e67e22; }
        .stat-card.bookings .number { color: #e67e22; }
        
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .menu-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            text-decoration: none;
            color: #2c3e50;
            transition: transform 0.2s;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .menu-card h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .menu-card p {
            color: #7f8c8d;
        }
        .menu-card.manage-houses { border-top: 4px solid #27ae60; }
        .menu-card.manage-users { border-top: 4px solid #9b59b6; }
        .menu-card.view-contacts { border-top: 4px solid #e67e22; }
        .menu-card.view-bookings { border-top: 4px solid #e67e22; }
        .menu-card.add-house { border-top: 4px solid #3498db; }
        
        .welcome-msg {
            background: #d5f4e6;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: #1e8449;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>📊 Admin Dashboard</h1>
            <div>
                <span>Welcome, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <div class="welcome-msg">
            <strong>👋 Welcome to the Nairobi House Hunting Admin Panel!</strong>
            Manage your houses, users, and contact messages from here.
        </div>
        
        <div class="stats-grid">
            <div class="stat-card houses">
                <h3>🏠 Total Houses</h3>
                <div class="number"><?php echo $houses_count; ?></div>
            </div>
            <div class="stat-card users">
                <h3>👥 Total Users</h3>
                <div class="number"><?php echo $users_count; ?></div>
            </div>
            <div class="stat-card contacts">
                <h3>📩 Contact Messages</h3>
                <div class="number"><?php echo $contacts_count; ?></div>
            </div>
            <div class="stat-card bookings">
                <h3>📅 Total Bookings</h3>
                <div class="number"><?php echo $bookings_count; ?></div>
            </div>
        </div>
        
        <h2 style="margin-bottom: 20px; color: #2c3e50;">📋 Management Menu</h2>
        <div class="menu-grid">
            <a href="manage_houses.php" class="menu-card manage-houses">
                <h3>🏠 Manage Houses</h3>
                <p>Add, edit, or remove house listings</p>
            </a>
            <a href="manage_users.php" class="menu-card manage-users">
                <h3>👥 Manage Users</h3>
                <p>View and manage registered users</p>
            </a>
            <a href="view_contacts.php" class="menu-card view-contacts">
                <h3>📩 View Contacts</h3>
                <p>View customer contact messages</p>
            </a>
            <a href="view_bookings.php" class="menu-card view-bookings">
                <h3>📅 View Bookings</h3>
                <p>View and manage house bookings</p>
            </a>
        </div>
    </div>
</body>
</html>
