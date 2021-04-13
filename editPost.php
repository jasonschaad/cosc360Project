<?php
// Start the session
session_start();

$PAGENAME = 'Edit Post';
$HEAD = '
<script type="text/javascript" src="scripts/validate.js"></script>

';

$categoryID = $_GET['postID']; 
$postID = $_GET['postID']; 

if (empty($categoryID) || (empty($postID))) {
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

array_push($BREADCRUMB, array("href" => "editPost.php", "name" => "Edit Post"));

// $BREADCRUMB =  array(
//   array("href" => "login.php", "name" => "Login")
// );

include 'head.php';

  $sql = "SELECT title, postContent FROM posts WHERE ID = ?";
  $preparedStatement = mysqli_prepare($connection, $sql);
  if ($preparedStatement === false) {
    die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
  }
  
  mysqli_stmt_bind_param($preparedStatement, "i", $postID); 
  mysqli_stmt_execute($preparedStatement);
  mysqli_stmt_bind_result($preparedStatement, $title, $postContent);
  mysqli_stmt_fetch($preparedStatement);
  
  // Close the statement
  mysqli_stmt_close($preparedStatement);
   
  // Close the database
  mysqli_close($connection);
  
  echo "<br />";
  
  // Build the form
  echo "<form method='post' action='editPost_process.php' id='mainForm' >\n";
  echo "<fieldset>\n";
  echo "<legend>Edit Post</legend>\n";
  echo "<label for='title'>Title:</label>\n";
  echo "<br />";
  echo "<textarea id = 'postTitle' name = 'postTitle' rows = '1' cols = '50'>\n";
  echo "$title";
  echo "</textarea>";
  echo "<br />";
  echo "<label for='content'>Content:</label>\n";
  echo "<br />";
  echo "<textarea id = 'postContent' name = 'postContent' rows = '3' cols = '50'>\n";
  echo "$postContent";
  echo "</textarea>";
  
  echo "<input type='hidden' id='postID' name='postID' value='$postID'>\n";
  echo "<input type='hidden' id='categoryID' name='categoryID' value='$categoryID'>\n";
  echo "<br />\n";
   
  echo "<br />\n";
  echo "<input type='submit' value='Edit Post'>";
  echo "</fieldset>";
  echo "</form>";  


include 'foot.php';

?>
