<?php
// Start the session
session_start();

if (!isset($_SESSION['securityLevel']) && $_SESSION['securityLevel'] != 2) {  
  $output = "<p>You must be an admin to access this page.</p>";
  $output .= "<p><a href='index.php'>Return home and try again</a></p>";
  exit($output);
}

$PAGENAME = 'Edit Reply';
$HEAD = '
<script type="text/javascript" src="scripts/validate.js"></script>

';

$categoryID = $_GET['categoryID']; 
$replyID = $_GET['replyID']; 
$postID = $_GET['postID']; 

if (empty($replyID) || empty($postID) || empty($categoryID)) {
   // get bent
   $output = "<p>One of the required GET variables is not as expected.</p>";
   $output .= "<p><a href='home.php'>Return to the home page</a></p>";
   exit($output);
}

// All good we can connect to database now
include 'dbhosts.php';

// Get category Name for breadcrumb
$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null) {
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
} 

$sql = "SELECT categoryName FROM category WHERE ID = ?";
$preparedStatement = mysqli_prepare($connection, $sql);
if ($preparedStatement === false) {
  die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
}

mysqli_stmt_bind_param($preparedStatement, "i", $categoryID); 
mysqli_stmt_execute($preparedStatement);
mysqli_stmt_bind_result($preparedStatement, $categoryName);
mysqli_stmt_fetch($preparedStatement);

// Close the statement
mysqli_stmt_close($preparedStatement);


$BREADCRUMB =  array(
  array("name" => "Categories"),
  array("href" => "forumPost.php?categoryID=$categoryID", "name" => "$categoryName")
);

array_push($BREADCRUMB, array("href" => "editPost.php", "name" => "Edit Reply"));

// $BREADCRUMB =  array(
//   array("href" => "login.php", "name" => "Login")
// );

include 'head.php';

  $sql = "SELECT Content FROM replies WHERE ID = ?";
  $preparedStatement = mysqli_prepare($connection, $sql);
  if ($preparedStatement === false) {
    die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
  }
  
  mysqli_stmt_bind_param($preparedStatement, "i", $replyID); 
  mysqli_stmt_execute($preparedStatement);
  mysqli_stmt_bind_result($preparedStatement, $replyContent);
  mysqli_stmt_fetch($preparedStatement);
  
  // Close the statement
  mysqli_stmt_close($preparedStatement);
   
  // Close the database
  mysqli_close($connection);
  
  echo "<br />";
  
  // Build the form
  echo "<form method='post' action='editReply_process.php' id='mainForm' >\n";
  echo "<fieldset>\n";
  echo "<legend>Edit Reply</legend>\n";
  echo "<label for='content'>Content:</label>\n";
  echo "<br />";
  echo "<textarea id = 'replyContent' name = 'replyContent' rows = '3' cols = '50'>\n";
  echo "$replyContent";
  echo "</textarea>";
  
  echo "<input type='hidden' id='postID' name='postID' value='$postID'>\n";
  echo "<input type='hidden' id='replyID' name='replyID' value='$replyID'>\n";
  echo "<input type='hidden' id='categoryID' name='categoryID' value='$categoryID'>\n";
  echo "<br />\n";
   
  echo "<br />\n";
  echo "<input type='submit' value='Edit Reply'>";
  echo "</fieldset>";
  echo "</form>";  


include 'foot.php';

?>
