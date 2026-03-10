<?php
include '../db.php';

if(isset($_POST['add'])){

$location=$_POST['location'];
$price=$_POST['price'];
$type=$_POST['type'];
$description=$_POST['description'];

$query="INSERT INTO houses(location,price,house_type,description)
VALUES('$location','$price','$type','$description')";

mysqli_query($conn,$query);

echo "House added successfully";

}
?>

<form method="POST">

<input type="text" name="location" placeholder="Location"><br>
<input type="number" name="price" placeholder="Price"><br>

<select name="type">
<option>Bedsitter</option>
<option>1 Bedroom</option>
<option>2 Bedroom</option>
<option>3 Bedroom</option>
</select>

<textarea name="description" placeholder="Description"></textarea>

<button name="add">Add House</button>

</form>
