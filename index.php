<!DOCTYPE html>
<html>
<?php 
session_start();
if(isset($_GET['booking']) && $_GET['booking'] == 'success') {
    echo "<div style='background: #27ae60; color: white; padding: 15px; text-align: center; margin-bottom: 20px;'>House booked successfully! We will contact you soon.</div>";
}
$title = "House Hunting System"; 
include 'header.php'; ?>
    <section class="hero">
    <h2>Find Your Dream Home in Nairobi</h2>
    <p>Explore verified houses with prices, amenities, and locations</p>
    <a href="houses.php" class="btn">View Houses</a>
</section>
    <section class="featured-houses">
    <h3>Available Houses</h3>
<?php
include 'db.php';

$query="SELECT * FROM houses WHERE id NOT IN (SELECT house_id FROM bookings WHERE status = 'confirmed') LIMIT 4";
$result=mysqli_query($conn,$query);

while($row=mysqli_fetch_assoc($result)){
    echo "<div class='house-card'>";
    echo "<img src='" . ($row['image'] ? $row['image'] : 'images/placeholder.jpg') . "' alt='House'>";
    echo "<h4>".$row['house_type']."</h4>";
    echo "<p>Location: ".$row['location']."</p>";
    echo "<p>Ksh ".$row['price']."</p>";
    echo "<a href='confirm_booking.php?house_id={$row['id']}' class='btn' style='margin-top:10px;'>Book Now</a>";
    echo "</div>";
}
?>
</section>

<div style="text-align: center; margin: 20px 0;">
    <a href="houses.php" class="btn">View More Houses</a>
</div>

<?php include 'footer.php'; ?>
</html>
