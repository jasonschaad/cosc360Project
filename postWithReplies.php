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

$sql = "SELECT postTitle, postDate, postContent, users.ID FROM posts JOIN users ON postUserId = users.ID WHERE postId = $postId";
$result = mysqli_query($connection, $sql);
while ($row = mysqli_fetch_assoc($result))
    {
    $tempTitle = $row['postTitle'];
    $tempDate = $row['postDate'];
    $tempContent = $row['postContent'];
    $tempUserID = $row['users.ID'];
    echo"<article>";
    echo"<h3 style = 'text-align: center;'>$tempTitle</h3>";
    echo"<div>";
    echo "$tempContent";

    }
?>

</body>
</html>