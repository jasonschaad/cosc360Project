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
//add code to verify security level so non-admins cannot delete post via modifying url
if(!isset($_SESSION['securityLevel']) || $_SESSION["securityLevel"] != 2){
    echo"Error!";
}
else{
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
    $postID = $_GET["postID"];
    $categoryID = $_GET["categoryID"];
    $sql = "DELETE FROM posts WHERE ID = ?";
    $preparedStatement = mysqli_prepare($connection, $sql);
        if ($preparedStatement === false) {
            die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
        }
    mysqli_stmt_bind_param($preparedStatement, "s", $postID); 
    mysqli_stmt_execute($preparedStatement);
    mysqli_stmt_close($preparedStatement);
    $sql = "DELETE FROM replies WHERE replyPostId = ?";
    $preparedStatement = mysqli_prepare($connection, $sql);
        if ($preparedStatement === false) {
            die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
        }
    mysqli_stmt_bind_param($preparedStatement, "s", $postID); 
    mysqli_stmt_execute($preparedStatement);
    mysqli_stmt_close($preparedStatement);
    mysqli_close($connection);
    header("Location: forumPost.php?categoryID=$categoryID");

    }

?>
</body>
</html>
