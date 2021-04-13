<?php
// Start the session
session_start();

if (!isset($_SESSION['securityLevel']) && $_SESSION['securityLevel'] != 2) {  
  $output = "<p>You must be an admin to access this page.</p>";
  $output .= "<p><a href='index.php'>Return home and try again</a></p>";
  exit($output);
}

include 'head.php';

// Confirm we have a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input fields
  $postID = $_POST['postID']; 
  $categoryID = $_POST['categoryID']; 
  $postTitle = $_POST['postTitle']; 
  $postContent = $_POST['postContent']; 
} 
else {
  // error message if not a post (prevents data being injected with a GET)
  $output = "<p>You may only access this page using the form provided.</p>";
  $output .= "<p><a href='index.php'>Return to the home page</a></p>";
  exit($output);
}

// Check if input fields are empty
$isEmpty = 0;

if (empty($categoryID)) {
  $isEmpty = 1;
}
if (empty($postID)) {
  $isEmpty = 1;
}
if (empty($postTitle)) {
  $isEmpty = 1;
}
if (empty($postContent)) {
  $isEmpty = 1;
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
  // good connection
  
  /////////////////////////////
  // Update title and content
  /////////////////////////////


  $sql = "UPDATE posts SET title = ?, postContent = ? WHERE ID = ?";
  
  $preparedStatement = mysqli_prepare($connection, $sql);
  if ($preparedStatement === false) {
    die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
  }
  mysqli_stmt_bind_param($preparedStatement, "ssi", $postTitle, $postContent, $postID);

  mysqli_stmt_execute($preparedStatement);
 
  // Close the statement
  mysqli_stmt_close($preparedStatement);
 
  // Close the database
  mysqli_close($connection);
  
  header("Location: forumPost.php?categoryID=$categoryID");
  
}

?>
