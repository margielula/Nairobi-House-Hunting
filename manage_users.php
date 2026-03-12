<?php
session_start();
$title = "Manage Users - Admin Dashboard";
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

// Handle delete user action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Prevent deleting yourself
    if (isset($_SESSION['username'])) {
        $current_user = $conn->query("SELECT id FROM users WHERE username = '" . $_SESSION['username'] . "'")->fetch_assoc();
        if ($current_user && $current_user['id'] != $id) {
            $conn->query("DELETE FROM users WHERE id = $id");
        }
    }
    header("Location: manage_users.php");
    exit;
}

// Get all users
$users_result = $conn->query("SELECT id, username, email, role FROM users ORDER BY id DESC");
$users = [];
while ($row = $users_result->fetch_assoc()) {
    $users[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="index.html code/style.css">
    <style>
        .manage-container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-header h1 { color: #2c3e50; }
        .back-btn { background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        
        .table-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .table-card h3 { color: #2c3e50; margin-bottom: 20px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #9b59b6; color: white; }
        tr:hover { background: #f5f5f5; }
        
        .actions { display: flex; gap: 5px; }
        .btn { padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; text-decoration: none; }
        .btn-delete { background: #e74c3c; color: white; }
        
        .empty-state { text-align: center; padding: 40px; color: #7f8c8d; }
        
        .user-count { background: #9b59b6; color: white; padding: 5px 15px; border-radius: 20px; font-size: 14px; }
        
        .user-info { display: flex; flex-direction: column; }
        .user-email { font-size: 12px; color: #7f8c8d; }
        
        .current-user { background: #d5f4e6; }
        .current-user td { font-weight: bold; color: #1e8449; }
        
        .actions-section { margin-top: 30px; }
        .actions-section h3 { color: #2c3e50; margin-bottom: 15px; }
        .user-action-item { display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #ddd; background: white; border-radius: 5px; margin-bottom: 5px; }
        .user-action-item:hover { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="manage-container">
        <div class="page-header">
            <h1>👥 Manage Users</h1>
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>
        
        <div class="table-card">
            <h3>📋 All Registered Users <span class="user-count"><?php echo count($users); ?></span></h3>
            
            <?php if (count($users) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr <?php echo ($user['username'] == $_SESSION['username']) ? 'class="current-user"' : ''; ?>>
                                <td><?php echo $user['id']; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($user['username']); ?>
                                    <?php if (isset($_SESSION['username']) && $user['username'] == $_SESSION['username']): ?>
                                        <span style="color: #27ae60; font-size: 11px;">(You)</span>
                                    <?php endif; ?>
                                </td>
                                <td class="user-info">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td class="actions">
                                    <?php if (isset($_SESSION['username']) && $user['username'] != $_SESSION['username']): ?>
                                        <a href="?delete=<?php echo $user['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                    <?php else: ?>
                                        <span style="color: #7f8c8d; font-size: 12px;">Cannot delete</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>No users found. Register your first user!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
