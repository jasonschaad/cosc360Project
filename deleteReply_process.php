<?php
// Start the session
session_start();

include 'head.php';

//add code to verify security level so non-admins cannot delete post via modifying url
if (!isset($_SESSION['securityLevel']) || $_SESSION["securityLevel"] != 2) {
    echo"you need to be an admin to access this page. </br>";
    echo"<a href = 'index.php'>Return to Home Page</a>";
} else {
    
    include 'dbhosts.php';
    //make connection
    $connection = mysqli_connect($host, $user, $password, $database);

    $error = mysqli_connect_error();
    //check for database connection error
    if($error != null) {
        $output = "<p>Unable to connect to database!</p>";
    exit($output);
    } 
    $replyID = $_GET["replyID"];
    $postID = $_GET["postID"];
    $sql = "DELETE FROM replies WHERE ID = ?";
    $preparedStatement = mysqli_prepare($connection, $sql);
    if ($preparedStatement === false) {
        die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
    }
    mysqli_stmt_bind_param($preparedStatement, "s", $replyID); 
    mysqli_stmt_execute($preparedStatement);
    mysqli_stmt_close($preparedStatement);
    mysqli_close($connection);
    header("Location: forumReply.php?postID=$postID");
    
}

include 'foot.php';

?>
