<?php
// Start the session
session_start();

$PAGENAME = 'Password Reset';

$BREADCRUMB =  array(
  array("href" => "login.php", "name" => "Login"),
  array("name" => "Reset Password")
);
include 'head.php';

function randomPassword() {
  $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
  $pass = array(); 
  $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
  for ($i = 0; $i < 8; $i++) {
      $n = rand(0, $alphaLength);
      $pass[] = $alphabet[$n];
  }
  return implode($pass); //turn the array into a string
}


// Confirm we have a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input fields
  $email = $_POST['email']; 
} 
else {
  // error message if not a post (prevents data being injected with a GET)
  $output = "<p>You may only access this page using the form provided.</p>";
  $output .= "<p><a href='forgotPassword.php'>Return to the forgot password page</a></p>";
  exit($output);
}

// Check if input fields are empty
$isEmpty = 0;

if (empty($email)) {
  $isEmpty = 1;
}
if ($isEmpty) {
  $output = "<p>One of the required POST variables is empty.</p>";
  $output .= "<p><a href='forgotPassword.php'>Return to the forgot password page</a></p>";
  exit($output);
}

// check if valid email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $output = "<p>email is not valid.</p>";
  $output .= "<p><a href='forgotPassword.php'>Return to the forgot password page</a></p>";
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
  
  // generate a new password
  $newPassword = randomPassword();

  $MD5Password = md5($newPassword);
 
  $sql = "SELECT COUNT(*),ID, firstName FROM users WHERE email = ?";
  $preparedStatement = mysqli_prepare($connection, $sql);
  if ($preparedStatement === false) {
    die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
  }
  
  mysqli_stmt_bind_param($preparedStatement, "s", $email); 
  mysqli_stmt_execute($preparedStatement);
  mysqli_stmt_bind_result($preparedStatement, $num_rows, $ID, $firstName);
  mysqli_stmt_fetch($preparedStatement);
  
  // Close the statement
  mysqli_stmt_close($preparedStatement);
   
  if ($num_rows == 1) {
    // email account was found
     
    // Update the password 
    $sql = "UPDATE users set password = ? WHERE ID = $ID";
    $preparedStatement = mysqli_prepare($connection, $sql);
    if ($preparedStatement === false) {
      die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
    }
    mysqli_stmt_bind_param($preparedStatement, "s", $MD5Password);
    mysqli_stmt_execute($preparedStatement);
    
    // Close the statement
    mysqli_stmt_close($preparedStatement);
    
    // Close the database
    mysqli_close($connection);
    // email user
    
    // the message
    $msg = "Hello $firstName,<br /><br /> 
      Your new password is $newPassword. Please login to Nerd Forum and change your password to something more secure.";
    
    // use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg,70);
    
    // send email
    mail($email,"Nerd Forum password reset",$msg);
    
    echo "An email with the following contents has been sent to $email: <br />\n";
    echo "<br /> === Email start === <br />\n"; 
    echo $msg;
    echo "<br /> === Email end === <br />\n"; 
    echo "<p><a href='index.php'>Return to Nerd Forum</a></p>";
  }
  else {
    echo "<p>The email was not found.</p>";
    echo "<p><a href='forgotPassword.php'>Return to the forgot password page.</a></p>";
  }
  
}

include 'foot.php';

?>
