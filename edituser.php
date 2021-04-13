<?php
// Start the session
session_start();

$PAGENAME = 'Edit User';
$HEAD = '
<script type="text/javascript" src="scripts/validate.js"></script>

<script type="text/javascript">

function checkPasswordMatch(e) {
  var password = document.getElementById("password");
  var passwordcheck = document.getElementById("password-check");
  if (password.value != passwordcheck.value) {
    alert("Passwords must be the same. Please try again.");
    makeRed(password);
    makeRed(passwordcheck);
    e.preventDefault();
  }
}

</script>
';
$BREADCRUMB =  array(
  array("href" => "login.php", "name" => "Login")
);

// OK if userID is empty - create user
if (empty($_GET)) {
  $userID = "";
  array_push($BREADCRUMB, array("href" => "edituser.php", "name" => "Create New Account"));
} 
else {
  $userID = $_GET['ID']; 
  array_push($BREADCRUMB, array("href" => "edituser.php?ID=$userID", "name" => "Account Settings"));
}

$isProblem = 0 ;

if (is_numeric($userID) || (empty($userID))) {
  // all good
}
else {
  // problem
  $isProblem = 1;
}

if ($isProblem) {
  $output = "<p>One of the required GET variables is not as expected.</p>";
  $output .= "<p><a href='home.php'>Return to the home page</a></p>";
  exit($output);
}

include 'head.php';
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
  $sql = "SELECT username, firstName, lastName, email, active FROM users WHERE ID = ?";
  $preparedStatement = mysqli_prepare($connection, $sql);
  if ($preparedStatement === false) {
    die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
  }
  
  mysqli_stmt_bind_param($preparedStatement, "i", $userID); 
  mysqli_stmt_execute($preparedStatement);
  mysqli_stmt_bind_result($preparedStatement, $username, $firstName, $lastName, $email, $active);
  mysqli_stmt_fetch($preparedStatement);
  
  // Close the statement
  mysqli_stmt_close($preparedStatement);
   
  // Close the database
  mysqli_close($connection);

  if (empty($userID)) {
    $tempValue = "Create User";
  }
  else {
    $tempValue = "Update User";
  }
  
  echo "<br />";
  
  // Build the form
  echo "<form method='post' action='edituser_process.php' id='mainForm' enctype='multipart/form-data'>\n";
  echo "<fieldset>\n";
  echo "<legend>$tempValue</legend>\n";
  echo "<label for='username'>Username:</label>\n";
  echo "<input type='text' name='username' id='username' class='required' value='$username'>\n";
  echo "<input type='hidden' id='userID' name='userID' value='$userID'>\n";
  echo "<br />\n";
  
  echo "<label for='firstName'>First Name:</label>\n";
  echo "<input type='text' name='firstName' id='firstName' class='required' value='$firstName'>\n";
  echo "<br />\n";
  
  echo "<label for='lastName'>Last Name:</label>\n";
  echo "<input type='text' name='lastName' id='lastName' class='required' value='$lastName'>\n";
  echo "<br />\n";
  
  echo "<label for='email'>Email:</label>\n";
  echo "<input type='text' name='email' id='email' class='required' value='$email'>\n";
  echo "<br />\n";
  
  if (empty($userID)) {
  
    echo "<label for='password'>Password:</label>\n";
    echo "<input type='password' name='password' id='password' class='required' />\n";
    echo "<br />\n";
    
    echo "<label for='password-check'>Re-enter Password:</label>\n";
    echo "<input type='password' name='password-check' id='password-check' class='required' />\n";
    echo "<br />\n";
  }
  
  // photo
  echo "<label for='photo'>Photo:</label>\n";

  $dir = "files";
  
  // fake query string to ensure photo is not cached after updating
  $nocache = rand(1,9999);
  
  if (file_exists("{$dir}/"."$userID.jpg")) {
    $isFileExists = 1;
    echo "<img src='files/$userID.jpg?$nocache' alt='photo' width='96px' height = '125px' />";
  }
  else {
    $isFileExists = 0;
    echo "<img src='images/anonymous.png?$nocache' alt='photo'  />\n ";
  }
  echo "&nbsp;<input type='file' name='userfile' id='userfile' value='edit photo' /> ";
  echo "<span class='fieldnote'>must be a jpg</span>";
  
  echo "<br />\n";
  
  if (isset($_SESSION['securityLevel']) && $_SESSION['securityLevel'] == 2) {
    
    echo "<label for='active'>Active:</label>\n";
    echo "<select name='active' id='active'>\n";
    if ($active) {
      echo "<option selected value=1>Yes</option>\n";
      echo "<option value=0>No</option>\n";
    } 
    else {
      echo "<option value=1>Yes</option>\n";
      echo "<option selected value=0>No</option>\n";
    }
    echo "</select>\n";
    echo "<br />\n";
  }
  
  echo "<br />\n";
  echo "<input type='submit' value='$tempValue'>";
  echo "</fieldset>";
  echo "</form>";
  
  if (!empty($userID)) {
    echo "<p><a href='changepassword.php'>Change password</a></p>\n";
  }
}

include 'foot.php';

?>
