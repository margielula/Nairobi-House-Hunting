<?php
session_start();
$title = "Manage Houses - Admin Dashboard";
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM houses WHERE id = $id");
    header("Location: manage_houses.php");
    exit;
}

// Handle add/edit action
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['image']['name']);
        $image_path = 'images/' . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }
    
    if (isset($_POST['add'])) {
        $location = $conn->real_escape_string($_POST['location']);
        $price = $conn->real_escape_string($_POST['price']);
        $house_type = $conn->real_escape_string($_POST['house_type']);
        $description = $conn->real_escape_string($_POST['description']);
        
        $conn->query("INSERT INTO houses (location, price, house_type, description, image) VALUES ('$location', '$price', '$house_type', '$description', '$image_path')");
    } elseif (isset($_POST['update'])) {
        $id = intval($_POST['id']);
        $location = $conn->real_escape_string($_POST['location']);
        $price = $conn->real_escape_string($_POST['price']);
        $house_type = $conn->real_escape_string($_POST['house_type']);
        $description = $conn->real_escape_string($_POST['description']);
        
        $update_query = "UPDATE houses SET location='$location', price='$price', house_type='$house_type', description='$description'";
        if ($image_path) {
            $update_query .= ", image='$image_path'";
        }
        $update_query .= " WHERE id=$id";
        
        $conn->query($update_query);
    }
}

// Get all houses
$houses_result = $conn->query("SELECT * FROM houses ORDER BY id DESC");
$houses = [];
while ($row = $houses_result->fetch_assoc()) {
    $houses[] = $row;
}

// Get house for editing if ID provided
$edit_house = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM houses WHERE id = $edit_id");
    if ($edit_result && $edit_result->num_rows > 0) {
        $edit_house = $edit_result->fetch_assoc();
    }
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
        
        .content-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }
        
        .form-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: fit-content;
        }
        .form-card h3 { color: #27ae60; margin-bottom: 20px; }
        .form-card h3.edit-mode { color: #e67e22; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #2c3e50; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-group textarea { height: 80px; }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary { background: #27ae60; color: white; }
        .btn-edit { background: #e67e22; color: white; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-cancel { background: #95a5a6; color: white; }
        
        .table-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .table-card h3 { color: #2c3e50; margin-bottom: 20px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #3498db; color: white; }
        tr:hover { background: #f5f5f5; }
        
        .actions { display: flex; gap: 5px; }
        .actions a { padding: 5px 10px; border-radius: 3px; text-decoration: none; font-size: 12px; }
        
        .empty-state { text-align: center; padding: 40px; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="manage-container">
        <div class="page-header">
            <h1>🏠 Manage Houses</h1>
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>
        
        <div class="content-grid">
            <!-- Add/Edit Form -->
            <div class="form-card">
                <h3 <?php if($edit_house) echo 'class="edit-mode"'; ?>>
                    <?php echo $edit_house ? '✏️ Edit House' : '➕ Add New House'; ?>
                </h3>
                <form method="POST" enctype="multipart/form-data">
                    <?php if ($edit_house): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_house['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" value="<?php echo $edit_house ? $edit_house['location'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Price (Ksh)</label>
                        <input type="number" name="price" value="<?php echo $edit_house ? $edit_house['price'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>House Type</label>
                        <select name="house_type">
                            <option value="Bedsitter" <?php echo ($edit_house && $edit_house['house_type'] == 'Bedsitter') ? 'selected' : ''; ?>>Bedsitter</option>
                            <option value="1 Bedroom" <?php echo ($edit_house && $edit_house['house_type'] == '1 Bedroom') ? 'selected' : ''; ?>>1 Bedroom</option>
                            <option value="2 Bedroom" <?php echo ($edit_house && $edit_house['house_type'] == '2 Bedroom') ? 'selected' : ''; ?>>2 Bedroom</option>
                            <option value="3 Bedroom" <?php echo ($edit_house && $edit_house['house_type'] == '3 Bedroom') ? 'selected' : ''; ?>>3 Bedroom</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description"><?php echo $edit_house ? $edit_house['description'] : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" accept="image/*">
                        <?php if ($edit_house && $edit_house['image']): ?>
                            <p>Current image: <img src="<?php echo $edit_house['image']; ?>" width="100"></p>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" name="<?php echo $edit_house ? 'update' : 'add'; ?>" class="btn <?php echo $edit_house ? 'btn-edit' : 'btn-primary'; ?>">
                        <?php echo $edit_house ? 'Update House' : 'Add House'; ?>
                    </button>
                    
                    <?php if ($edit_house): ?>
                        <a href="manage_houses.php" class="btn btn-cancel">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <!-- Houses List -->
            <div class="table-card">
                <h3>📋 All Houses (<?php echo count($houses); ?>)</h3>
                
                <?php if (count($houses) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($houses as $house): ?>
                                <tr>
                                    <td><?php echo $house['id']; ?></td>
                                    <td><img src="<?php echo $house['image']; ?>" width="50"></td>
                                    <td><?php echo htmlspecialchars($house['location']); ?></td>
                                    <td><?php echo htmlspecialchars($house['house_type']); ?></td>
                                    <td>Ksh <?php echo number_format($house['price']); ?></td>
                                    <td class="actions">
                                        <a href="?edit=<?php echo $house['id']; ?>" class="btn-edit">Edit</a>
                                        <a href="?delete=<?php echo $house['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this house?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <p>No houses found. Add your first house using the form!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
