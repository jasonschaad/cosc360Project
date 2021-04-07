<?php
// Start the session
session_start();
?>
<!DOCTYPE html>

<html>
<head>
    <style>
        /* added temporary styles for visualization :) */
        #table-test td{border: 1px solid black;}
        #table-test{border: 1px solid black; margin-left: auto; margin-right: auto; width: 80%; text-align: center;}
        h1 {text-align: center;}
        article{border: 1px solid black;}
        
    </style>
    <?php include('head.php'); ?>
    <title>Nerd Forum</title>
</head>
<body>
<?php

    include 'header.php';

    include 'dbhosts.php';
    $connection = mysqli_connect($host, $user, $password, $database);

    $error = mysqli_connect_error();
    if($error != null) {
    $output = "<p>Unable to connect to database!</p>";
    exit($output);
    } 
    $postId = $_GET["postID"];

    $sql = "SELECT title, DATE_FORMAT(postDate, '%M %e, %Y %l:%i %p') as formattedDate, postContent, users.username AS userName FROM posts JOIN users ON postUserId = users.ID WHERE posts.ID = ?";
    $preparedStatement = mysqli_prepare($connection, $sql);
    if ($preparedStatement === false) {
        die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
    }
    mysqli_stmt_bind_param($preparedStatement, "s", $postId); 
    mysqli_stmt_execute($preparedStatement);
    mysqli_stmt_bind_result($preparedStatement, $postTitle, $postDate, $postContent, $author);
    echo"<div class = 'placeholder-post-container'>";
    while (mysqli_stmt_fetch($preparedStatement)){
        echo"<article class = 'placeholder-user-info'>";
        echo"<i>Posted by: $author on $postDate</i>";
        //echo"<figure><img src = '$postID.jpg'></img></figure>";
        echo"</article>";
        echo"<article class = 'placeholder-main-content'>";
        echo"<h3>$postTitle</h3>";
        echo "$postContent";
        echo"</article>";
        break;

    }
    echo"</div>";
    mysqli_stmt_close($preparedStatement);

    $sql = "SELECT content, DATE_FORMAT(replyDate, '%M %e, %Y %l:%i %p'), users.username AS userName FROM replies JOIN users ON replyUserId = users.ID WHERE replyPostid = ?";
    $preparedStatement = mysqli_prepare($connection, $sql);
    if ($preparedStatement === false) {
        die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
    }
    mysqli_stmt_bind_param($preparedStatement, "s", $postId); 
    mysqli_stmt_execute($preparedStatement);
    mysqli_stmt_bind_result($preparedStatement, $replyContent, $replyDate, $author);
    while (mysqli_stmt_fetch($preparedStatement)){
        echo"<div class = 'placeholder-post-container'>";
        echo"<article class = 'placeholder-user-info'>";
        echo"<i>Posted by: $author on $replyDate</i>";
        //echo"<figure><img src = '$postID.jpg'></img></figure>";
        echo"</article>";
        echo"<article class = 'placeholder-main-content'>";
        echo "$replyContent";
        echo"</article>";
        echo"</div>";
    }
    mysqli_stmt_close($preparedStatement);

    //##MODIFY CODE LATER - add if statement to check if users are logged in (Security level > 0).
    

 


    mysqli_close($connection);

?>

</body>
</html>