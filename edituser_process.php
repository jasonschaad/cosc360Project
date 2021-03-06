<?php
// Start the session
session_start();

include 'head.php';

// Confirm we have a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input fields
  $username = $_POST['username']; 
  $firstName = $_POST['firstName']; 
  $lastName = $_POST['lastName']; 
  $email = $_POST['email']; 
  $userID = $_POST['userID'];
  if (isset($_SESSION['securityLevel']) && $_SESSION['securityLevel'] == 2) {  
    $active = $_POST['active'];
    if (($active != 0) && ($active != 1)) {
      $output = "<p>You can only access this page from the user creation form.</p>";
      $output .= "<p><a href='index.php'>Return home and try again</a></p>";
      exit($output);
    }
  }
} 
else {
  // error message if not a post (prevents data being injected with a GET)
  $output = "<p>You may only access this page using the form provided.</p>";
  $output .= "<p><a href='index.php'>Return to the home page</a></p>";
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

// check if valid email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $output = "<p>email is not valid.</p>";
  $output .= "<p><a href='index.php'>Return to the home page</a></p>";
  exit($output);
} 

if ($userID == "") {
  // we are creating a user
  $type = "create";
  // grab password
  $pass = addslashes($_POST['password']); 
  if (empty($pass)) {
    $isEmpty = 1;
  }
}
else {
  // we are updating a user
  $type = "update";
}

// Check for empty variables
if ($isEmpty) {
  $output = "<p>One of the required POST variables is empty.</p>";
  $output .= "<p><a href='home.php'>Return to the home page</a></p>";
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
  
  if ($type == "create") {
    //////////////////////
    // Create user logic
    //////////////////////
    
    //////////////////////////////////////////////////////////////////////////////////
    // Check to see if the user already exists in the database based on email address
    //////////////////////////////////////////////////////////////////////////////////
        
    $sql = "SELECT COUNT(*) FROM users WHERE email = ? OR username = ?";
    $preparedStatement = mysqli_prepare($connection, $sql);
    if ($preparedStatement === false) {
      die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
    }
    
    mysqli_stmt_bind_param($preparedStatement, "ss", $email, $username); 
    mysqli_stmt_execute($preparedStatement);
    mysqli_stmt_bind_result($preparedStatement, $num_rows);
    mysqli_stmt_fetch($preparedStatement);
    
    // Close the statement
    mysqli_stmt_close($preparedStatement);
        
    if ($num_rows > 0) {
      $output = "<p>User already exists with this username and/or email.</p>";
      $output .= "<p><a href='home.php'>Return to the home page</a></p>";
      mysqli_close($connection);
      exit($output);
    }
    
    ///////////////////////////////////////////
    // Add user information into the database
    ///////////////////////////////////////////
    
    // Hash the password with MD5
    $MD5Password = md5($pass);
    
    $sql = "INSERT INTO users (username, firstName, lastName, email, password, securityLevel, creationDate, active) VALUES (?,?,?,?,?,1,NOW(), 1)";
    
    $preparedStatement = mysqli_prepare($connection, $sql);
    if ($preparedStatement === false) {
       die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
    }
    mysqli_stmt_bind_param($preparedStatement, "sssss", $username, $firstName, $lastName, $email, $MD5Password);
    mysqli_stmt_execute($preparedStatement);
   
    // grab the insert ID of the previous record
    $userID = mysqli_insert_id($connection);

    // Close the statement
    mysqli_stmt_close($preparedStatement);
  
    // Close the database
    mysqli_close($connection);
  }
  else {
    ////////////////////////////////////////////////
    // Update username, firstName, lastName, email
    ////////////////////////////////////////////////
    // Update the user except password
    if (isset($_SESSION['securityLevel']) && $_SESSION['securityLevel'] == 2) { 
      $sql = "UPDATE users SET username=?, firstName=?, lastName=?, email=?, active=? WHERE id=?";
    }
    else {
      $sql = "UPDATE users SET username=?, firstName=?, lastName=?, email=? WHERE id=?";
    }
    $preparedStatement = mysqli_prepare($connection, $sql);
    if ($preparedStatement === false) {
      die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
    }
    if (isset($_SESSION['securityLevel']) && $_SESSION['securityLevel'] == 2) { 
     mysqli_stmt_bind_param($preparedStatement, "ssssii", $username, $firstName, $lastName, $email, $active, $userID, );
    }
    else {
      mysqli_stmt_bind_param($preparedStatement, "ssssi", $username, $firstName, $lastName, $email, $userID);
    }
    
    mysqli_stmt_execute($preparedStatement);
   
    // Close the statement
    mysqli_stmt_close($preparedStatement);
   
    // Close the database
    mysqli_close($connection);
  }
  
  ////////////////////////////////////
  // conditionally upload photo 
  ////////////////////////////////////
   
  $errorNumber = 0;
  
  if ($_FILES["userfile"]["name"] != '') { 
    
    $pieces = explode(".", $_FILES["userfile"]["name"]);
    if (($pieces[1] != "jpg") && ($pieces[1] != "jpeg")) {
      $output = "<p>The photo must be in a jpeg format.</p>";
      $output .= "<p><a href='index.php'>Return to home</a></p>";
      exit($output);
    }
    
    $dir = "files";
     
    // Set Max File Size 1MB
    $MaxFileSize = 1000000;
    
    // Upload photo
    if ($_FILES["userfile"]["size"] < $MaxFileSize) {    
      move_uploaded_file($_FILES["userfile"]["tmp_name"],"{$dir}/" . $userID . ".jpg");
    }
    else {
      $errorNumber = 1;
    }  
  }
  
  header("Location: edituser_process2.php?type=$type&error=$errorNumber&fname=$firstName&lname=$lastName");
  
}

include 'foot.php';

?>
