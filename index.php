<?php
// Start the session
session_start();
?>
<!DOCTYPE html>

<html lang="en-US">
<head>
    <style>
        /* added temporary styles for visualization :) */
        #table-test td{border: 1px solid black;}
        #table-test{border: 1px solid black; margin-left: auto; margin-right: auto; width: 80%; text-align: center;}
        h1, h2{text-align: center;}
        
    </style>
    <?php include('head.php'); ?>
    <title>Nerd Forum</title>
</head>
<body>

<?php

include 'header.php';

echo "<h1>Welcome to NerdForum</h1>";
//creating table to display all post categories.
echo"<table id = 'table-test'>";
echo"<tr>";
echo"<th>Categories</th>";
echo"</tr>";
//make connection to database
include 'dbhosts.php';
$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null) {
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
} 



//continue doing things if no error.
$sql = "SELECT ID,categoryName from category";
$result = mysqli_query($connection, $sql);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo"<tr>";
        $tempCategory = $row["categoryName"];
        $tempID= $row["ID"];
        //echo link with categoryID to forumPost.php
        echo"<td><a href='forumPost.php?categoryID=$tempID'</a>$tempCategory</td>";
        echo"</tr>";
    }
}
echo"</table>";

$sql = "select count(replies.ID) as numReply, replyPostId from replies where replyDate >= DATE_ADD(CURDATE(), INTERVAL -1 DAY) group by replyPostId ORDER BY numReply DESC LIMIT 2";
$result = mysqli_query($connection, $sql);
if (mysqli_num_rows($result) > 0) {
    echo"<h2 style = >Hot Posts!!!</h2>";
    echo"<table id = 'table-test'>";
    echo"<tr>";
    echo"<th>Category</th>";
    echo"<th>Title</th>";
    echo"<th>Author</th>";
    echo"<th>Post Date</th>";
    echo"<th>Replies in Past Day</th>";
    
    echo"</tr>";
    while($row = mysqli_fetch_assoc($result)) {
        echo"<tr>";
        $tempNumReply = $row["numReply"];
        $tempPostID= $row["replyPostId"];
        $sql2 = "SELECT categoryName, category.ID, username, title, DATE_FORMAT(postDate, '%M %e, %Y %l:%i %p') AS postDate FROM category JOIN posts ON category.ID = posts.postCategoryId JOIN users ON users.ID = posts.postUserId WHERE posts.ID =$tempPostID";
        $result2 = mysqli_query($connection, $sql2);
        
        while($row2 = mysqli_fetch_assoc($result2)){
        $tempCategoryName = $row2["categoryName"];
        $tempTitle = $row2["title"];
        $tempUsername = $row2["username"];
        $tempPostDate = $row2["postDate"];
        
        }
        echo"<td>$tempCategoryName</td>";
        echo"<td><a href='forumReply.php?postID=$tempPostID'</a>$tempTitle</td>";
        echo"<td>$tempUsername</td>";
        echo"<td>$tempPostDate</td>";
        echo"<td>$tempNumReply</td>";
        echo"</tr>";
        
    }
    echo"</table>";
    mysqli_free_result($result2);
}else{
    echo"<h2>no hot posts to show right now!</h2>";
}

mysqli_free_result($result);
//close connection.
mysqli_close($connection);

?>


</body>
</html>
