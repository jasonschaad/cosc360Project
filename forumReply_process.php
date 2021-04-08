<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Nerd Forum</title>
<?php include ('head.php'); ?>
</head>
<body>
<?php 

include('header.php');
include 'dbhosts.php';
//make connection
$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
//check for database connection error
if($error != null) {
$output = "<p>Unable to connect to database!</p>";
exit($output);
} 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input fields
    $content = $_POST["replyContent"];
    $postId = $_POST["postID"];
} 
  else {
    // error message if not a post (prevents data being injected with a GET)
    $output = "<p>You may only access this page using the form provided.</p>";
    $output .= "<p><a href='index.php'>Return to home page</a></p>";
    exit($output);
}
//grab userID from session
$userId = $_SESSION["userID"];
//create insert statement, use current timestamp for replydate
$sql = "INSERT INTO replies(content, replyDate, replyUserId, replyPostId) VALUES(?,CURRENT_TIMESTAMP(),?,?)";
$preparedStatement = mysqli_prepare($connection, $sql);
    if ($preparedStatement === false) {
        die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
    }

mysqli_stmt_bind_param($preparedStatement, "sss", $content, $userId, $postId); 
//code so if
if(mysqli_stmt_execute($preparedStatement)== true){
    //echo"successfully posted reply!"; used for testing, does not display due to header() function called few lines later
    mysqli_stmt_close($preparedStatement);
    mysqli_close($connection);
    header("Location: forumReply.php?postID=$postId");
}
else{
    echo"prepared statement failed to execute.";
    mysqli_stmt_close($preparedStatement);
    mysqli_close($connection);
    echo"<a href = 'forumReply.php?postID=$postId'>Return to Home</a>";

}
?>
</body>
</html>