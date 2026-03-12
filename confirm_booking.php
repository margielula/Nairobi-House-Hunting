<?php
session_start();
$title = "Confirm Booking";
include 'db.php';
include 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="index.html code/style.css">
</head>
<body>
    <div style="max-width: 800px; margin: 0 auto; padding: 20px;">
        <h1>Confirm Your Booking</h1>
        <div style="border: 1px solid #ddd; padding: 20px; border-radius: 10px; background: #f9f9f9;">
            <?php
            if (!isset($_GET['house_id'])) {
                header("Location: index.php");
                exit;
            }

            $house_id = intval($_GET['house_id']);

            // Get house details
            $house_result = $conn->query("SELECT * FROM houses WHERE id = $house_id");
            if (!$house_result || $house_result->num_rows == 0) {
                echo "House not found.";
                exit;
            }
            $house = $house_result->fetch_assoc();

            $conn->close();
            ?>
            <h2><?php echo htmlspecialchars($house['house_type']); ?></h2>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($house['location']); ?></p>
            <p><strong>Price:</strong> Ksh <?php echo number_format($house['price']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($house['description']); ?></p>
        </div>
        <form action="book_house.php" method="post" style="margin-top: 20px;">
            <input type="hidden" name="house_id" value="<?php echo $house_id; ?>">
            <label for="contact" style="display: block; margin-bottom: 5px;">Phone Number:</label>
            <input type="tel" name="contact" id="contact" placeholder="Enter your phone number" pattern="[0-9]+" title="Please enter only numbers." required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px;">
            <button type="submit" style="background: #27ae60; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px;">Confirm Booking</button>
            <a href="index.php" style="margin-left: 10px; color: #3498db;">Cancel</a>
        </form>
    </div>

<?php include 'footer.php'; ?>
</body>
</html>
