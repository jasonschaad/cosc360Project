<?php
// Start the session
session_start();

include 'head.php';



// Confirm we have a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input fields
  $oldPassword = addslashes($_POST['oldpassword']); 
  $newPassword = addslashes($_POST['newpassword']); 
} 
else {
  // error message if not a post (prevents data being injected with a GET)
  $output = "<p>You may only access this page using the form provided.</p>";
  $output .= "<p><a href='edituser.php'>Return to edit user page</a></p>";
  exit($output);
}

// Check if input fields are empty
$isEmpty = 0;

if (empty($oldPassword)) {
  $isEmpty = 1;
}
if (empty($newPassword)) {
  $isEmpty = 1;
}

if ($isEmpty) {
  $output = "<p>One of the required POST variables is empty.</p>";
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
  //good connection, so do your thing
  //////////////////////////////////////////////////////////////
  // Check to see if the username password combination is valid
  //////////////////////////////////////////////////////////////
  
  // Let's try some prepared statements for fun - overkill I think for this lab.
 
  $MD5OldPassword = md5($oldPassword);
  
  $userID = $_SESSION['userID'];
  
  $sql = "SELECT COUNT(*) FROM users WHERE ID = $userID AND password = ?";
  $preparedStatement = mysqli_prepare($connection, $sql);
  if ($preparedStatement === false) {
    die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
  }
  
  mysqli_stmt_bind_param($preparedStatement, "s", $MD5OldPassword); 
  mysqli_stmt_execute($preparedStatement);
  mysqli_stmt_bind_result($preparedStatement, $num_rows);
  mysqli_stmt_fetch($preparedStatement);
  
  // Close the statement
  mysqli_stmt_close($preparedStatement);
   
  if ($num_rows == 1) {
    // We can update the password here
    $MD5NewPassword = md5($newPassword);
    $sql = "UPDATE users SET password = ? WHERE ID = $userID";
    $preparedStatement = mysqli_prepare($connection, $sql);
    if ($preparedStatement === false) {
      die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
    }
    mysqli_stmt_bind_param($preparedStatement, "s", $MD5NewPassword);
    mysqli_stmt_execute($preparedStatement);
      
    // Close the statement
    mysqli_stmt_close($preparedStatement); 
     
     echo "<p>The user's password has been updated.</p>";
     echo "<p><img src='images/accept32.png'> The password has been successfully updated.</p>\n";
  }
  else {
    echo "<p>Old password was incorrect.</p>";
  }
  
  // Close the database
  mysqli_close($connection);
}

include 'foot.php';

?>
