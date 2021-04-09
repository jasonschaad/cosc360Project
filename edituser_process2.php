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

$type = $_GET['type'];
$error = $_GET['error'];
$firstName = $_GET['fname'];
$lastName = $_GET['lname'];

if (($type != "create") && ($type != "update")) {
  $output = "<p>You can only access this page from the user creation form.</p>";
  $output .= "<p><a href='index.php'>Return home and try again<</a></p>";
  exit($output);
}

if (empty($firstName) || empty($firstName)) {
  $output = "<p>You can only access this page from the user creation form.</p>";
  $output .= "<p><a href='index.php'>Return home and try again<</a></p>";
  exit($output);
}

if ($error) {
  echo "<p>File size must be 1MB or less. Your file was not processed.</p>\n";
}

if ($type == "create") {
  include('header.php'); 
  echo "<p><img src='images/accept32.png'> Successfully created user. Please login.</p>\n";
}
else {
  include('header.php');
  echo "<p><img src='images/accept32.png'> Successfully updated user. You may have to logout and back in again for change to show</p>\n";
}

?>
</body>
</html>
