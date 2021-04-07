<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<head>
<title>Nerd Forum</title>
<?php include ('head.php'); ?>
</head>
<body>
<?php

// Confirm we have a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input fields
  $username = addslashes($_POST['username']); 
  $firstName = addslashes($_POST['firstName']); 
  $lastName = addslashes($_POST['lastName']); 
  $email = addslashes($_POST['email']); 
  $pass = addslashes($_POST['password']); 
  $userID = $_POST['userID'];
} 
else {
  // error message if not a post (prevents data being injected with a GET)
  $output = "<p>You may only access this page using the form provided.</p>";
  $output .= "<p><a href='edituser.php'>Return to edit user page</a></p>";
  exit($output);
}

// Check if input fields are empty
$isEmpty = 0;

if (empty($username)) {
  $isEmpty = 1;
}
if (empty($firstName)) {
  $isEmpty = 1;
}
if (empty($lastName)) {
  $isEmpty = 1;
}

if ($isEmpty) {
  $output = "<p>One of the required POST variables is empty.</p>";
  $output .= "<p><a href='edituser.php'>Return to the edit user page</a></p>";
  exit($output);
}

// check if valid email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $output = "<p>email is not valid.</p>";
  $output .= "<p><a href='edituser.php'>Return to the edit user page</a></p>";
  exit($output);
} 

// All good we can connect to database now
include 'dbhosts.php';

$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null) {
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
} 
else {
  //good connection
  
  ////////////////////////////////////////////////
  // Update username, firstName, lastName, email
  ////////////////////////////////////////////////

  // Update the user except password
  $sql = "UPDATE users SET username=?, firstName=?, lastName=?, email=? WHERE id=?";
  $preparedStatement = mysqli_prepare($connection, $sql);
  if ($preparedStatement === false) {
    die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
  }
  mysqli_stmt_bind_param($preparedStatement, "ssssi", $username, $firstName, $lastName, $email, $userID);
  mysqli_stmt_execute($preparedStatement);
 
 // Close the statement
 mysqli_stmt_close($preparedStatement);
 
 ////////////////////////////////
 // Update password if required
 ////////////////////////////////
 
 if ($pass != '') {
   $MD5Password = md5($pass);
   $sql = "UPDATE users SET password=? WHERE id=?";
   $preparedStatement = mysqli_prepare($connection, $sql);
   if ($preparedStatement === false) {
     die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
   }
   mysqli_stmt_bind_param($preparedStatement, "si", $MD5Password, $userID);
   mysqli_stmt_execute($preparedStatement);
  
  // Close the statement
  mysqli_stmt_close($preparedStatement);
 }

  // Close the database
  mysqli_close($connection);
  
  ////////////////////////////////////
  // conditionally upload photo 
  ////////////////////////////////////
   
  $errorMessage = "";
  
  if ($_FILES["userfile"]["name"] != '') { 
    $dir = "files";
     
    // Set Max File Size 1MB
    $MaxFileSize = 1000000;
    
    // Upload photo
    if ($_FILES["userfile"]["size"] < $MaxFileSize) {    
      move_uploaded_file($_FILES["userfile"]["tmp_name"],"{$dir}/" . $userID . ".jpg");
    }
    else {
      $errorMessage = "File is too large, max file size is $MaxFileSize.";
    }  
  }
  
  // Update firstName and lastName Session variables
  $_SESSION["firstName"] = $firstName;
  $_SESSION["lastName"] = $lastName;
  
  include('header.php');
  
  if (!empty($errorMessage)) {
    echo "<p>$errorMessage</p>";
    }
  
  if ($userID == "") {
    echo "<p>Successfully created user.</p>\n"; 
  }
  else {
    echo "<p>Successfully updated user.</p>\n";
  }
}

?>
</body>
</html>
