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
        article, article h3 {border: 1px solid black;}
        
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

$sql = "SELECT title, DATE_FORMAT(postDate, '%M %e, %Y %l:%i %p') as formattedDate, postContent, users.username AS userName FROM posts JOIN users ON postUserId = users.ID WHERE posts.ID = $postId";
$result = mysqli_query($connection, $sql);
while ($row = mysqli_fetch_assoc($result))
    {
    $tempTitle = $row['title'];
    $tempDate = $row['formattedDate'];
    $tempContent = $row['postContent'];
    $tempUsername = $row['userName'];
    echo"<article>";
    echo"<h3 style = 'text-align: center;'>$tempTitle</h3>";
    echo"<p>Posted by: $tempUsername on $tempDate";
    echo"<div style = 'text-align: center;'>";
    echo "$tempContent";
    echo"</div>";
    echo"</article>";

    break;

    }

?>

</body>
</html>