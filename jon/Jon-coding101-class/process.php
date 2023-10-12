<?php
  
  echo "Request<br>";
  print_r($_REQUEST);
  echo "<br><br>Get<br>";
  print_r($_GET);
  echo "<br><br>Post<br>";
  print_r($_POST);

  $fname = $_REQUEST["fname"];


?>

<form action="https://gstaging.getuwired.us/engconcepts/1.1_Code_School/Jon/process.php" method="get" target="_blank">
  
<?php  if($_REQUEST['fname']){}else{
    echo '<label for="fname">First name: </label><input type="text" id="fname" name="fname" ><br><br>' ;
  }?>
  <label for="lname">Last name:</label>
  <input type="text" id="lname" name="lname" value="<?php echo $_REQUEST['lname'];?>"><br><br>
  <label for="phone">Phone:</label>
  <input type="text" id="phone" name="phone" value="<?php echo $_REQUEST['phone'];?>"><br><br>
  <input type="submit" value="Submit">
</form>