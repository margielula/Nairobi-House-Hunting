<?php
session_start();
$title = "View Contacts - Admin Dashboard";
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

// Handle delete contact action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM contacts WHERE id = $id");
    header("Location: view_contacts.php");
    exit;
}

// Get all contacts
$contacts_result = $conn->query("SELECT * FROM contacts ORDER BY id DESC");
$contacts = [];
while ($row = $contacts_result->fetch_assoc()) {
    $contacts[] = $row;
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
        
        .contacts-grid { display: grid; gap: 20px; }
        
        .contact-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #e67e22;
        }
        .contact-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .contact-info h3 { color: #2c3e50; margin: 0 0 5px 0; }
        .contact-info p { margin: 0; color: #7f8c8d; font-size: 14px; }
        .contact-phone { color: #3498db !important; }
        
        .contact-message {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .contact-message h4 { color: #2c3e50; margin: 0 0 10px 0; font-size: 14px; }
        .contact-message p { margin: 0; color: #555; line-height: 1.6; }
        
        .contact-date {
            color: #95a5a6;
            font-size: 12px;
        }
        
        .contact-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn { padding: 5px 12px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; text-decoration: none; }
        .btn-delete { background: #e74c3c; color: white; }
        
        .empty-state { 
            text-align: center; 
            padding: 60px 20px; 
            color: #7f8c8d; 
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .contact-count { 
            background: #e67e22; 
            color: white; 
            padding: 5px 15px; 
            border-radius: 20px; 
            font-size: 14px; 
        }
        
        .table-card h3 { color: #2c3e50; margin-bottom: 20px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #e67e22; color: white; }
        tr:hover { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="manage-container">
        <div class="page-header">
            <h1>📩 View Contact Messages</h1>
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>
        
        <div class="table-card">
            <h3>📋 Customer Messages <span class="contact-count"><?php echo count($contacts); ?></span></h3>
            
            <?php if (count($contacts) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                            <tr>
                                <td><?php echo $contact['id']; ?></td>
                                <td><?php echo htmlspecialchars($contact['name']); ?></td>
                                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                <td><?php echo htmlspecialchars($contact['phone']); ?></td>
                                <td>
                                    <?php 
                                        $msg = htmlspecialchars($contact['message']);
                                        echo strlen($msg) > 50 ? substr($msg, 0, 50) . '...' : $msg; 
                                    ?>
                                </td>
                                <td>-</td>
                                <td class="contact-actions">
                                    <a href="?delete=<?php echo $contact['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p style="font-size: 48px; margin-bottom: 20px;">📭</p>
                    <p style="font-size: 18px;">No contact messages yet.</p>
                    <p>Messages from customers will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
