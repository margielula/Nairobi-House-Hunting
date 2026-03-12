<?php
session_start();
$title = "Available Houses - House Hunting System";
include 'header.php';
?>
    <section class="featured-houses">
    <h3>Available Houses</h3>
<?php
include 'db.php';

$query="SELECT * FROM houses WHERE id NOT IN (SELECT house_id FROM bookings WHERE status = 'confirmed');";
$result=mysqli_query($conn,$query);

while($row=mysqli_fetch_assoc($result)){
    echo "<div class='house-card'>";
    echo "<img src='" . ($row['image'] ? $row['image'] : 'images/placeholder.jpg') . "' alt='House'>";
    echo "<h4>".$row['house_type']."</h4>";
    echo "<p>Ksh ".$row['price']."</p>";
    echo "<p>Location: ".$row['location']."</p>";
    echo "<p>Description: ".$row['description']."</p>";
    echo "<a href='confirm_booking.php?house_id={$row['id']}' class='btn' style='margin-top:10px;'>Book Now</a>";
    echo "</div>";
}
?>
</section>

<?php include 'footer.php'; ?>
</html>
