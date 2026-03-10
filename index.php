<?php
include 'db.php';

$query="SELECT * FROM houses";
$result=mysqli_query($conn,$query);

while($row=mysqli_fetch_assoc($result)){

echo "<h3>".$row['house_type']."</h3>";
echo "Location: ".$row['location']."<br>";
echo "Price: ".$row['price']."<br>";
echo "<hr>";

}
?>
