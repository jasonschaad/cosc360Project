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
  $username = $_POST['username']; 
  $pass = $_POST['password']; 
} 
else {
  // error message if not a post (prevents data being injected with a GET)
  $output = "<p>You may only access this page using the form provided.</p>";
  $output .= "<p><a href='login.php'>Return to login page</a></p>";
  exit($output);
}

// Check if input fields are empty
$isEmpty = 0;

if (empty($username)) {
  $isEmpty = 1;
}
if (empty($pass)) {
  $isEmpty = 1;
}

if ($isEmpty) {
  $output = "<p>One of the required POST variables is empty.</p>";
  $output .= "<p><a href='login.php'>Return to the login page</a></p>";
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
  //good connection, so do your thing
  //////////////////////////////////////////////////////////////
  // Check to see if the username password combination is valid
  //////////////////////////////////////////////////////////////
  
  // Let's try some prepared statements for fun - overkill I think for this lab.
 
  $MD5Password = md5($pass);
 
  $sql = "SELECT COUNT(*),securityLevel, firstName, lastName, ID, active FROM users WHERE username = ? AND password = ?";
  $preparedStatement = mysqli_prepare($connection, $sql);
  if ($preparedStatement === false) {
    die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
  }
  
  mysqli_stmt_bind_param($preparedStatement, "ss", $username, $MD5Password); 
  mysqli_stmt_execute($preparedStatement);
  mysqli_stmt_bind_result($preparedStatement, $num_rows, $securityLevel, $firstName, $lastName, $userID, $active);
  mysqli_stmt_fetch($preparedStatement);
  
  // Close the statement
  mysqli_stmt_close($preparedStatement);
   
  // Close the database
  mysqli_close($connection);
  
  
   
  if ($num_rows == 1) {
   
   // check for active / inactive
   if (!$active) {
     $output = "<p>User is inactive.</p>";
     exit($output);  
   }
   echo "<p>User has a valid account.</p>";
   
   // Set session variables
   $_SESSION["securityLevel"] = $securityLevel;
   $_SESSION["firstName"] = $firstName;
   $_SESSION["lastName"] = $lastName;
   $_SESSION["userID"] = $userID;
   
   header("Location: index.php");
  }
  else {
    echo "<p>The username and/or password are invalid.</p>";
    // not authenticated
    $_SESSION["SecurityLevel"] = 0;
  }
  
}

?>
</body>
</html>
