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
<title>Nerd Forum</title>
<?php include('head.php');?>
</head>
<body>
<?php
include 'header.php';

$categoryID = $_GET["categoryID"];



include 'dbhosts.php';

$connection = mysqli_connect($host, $user, $password, $database);
$connection2 = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null) {
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
} 
$sql = "SELECT title, CAST(postContent AS VARCHAR(100)), users.username, postDate, posts.ID FROM posts JOIN users ON postUserId = users.ID WHERE postCategoryId = ?";
$preparedStatement = mysqli_prepare($connection, $sql);
if ($preparedStatement === false) {
    die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
}
mysqli_stmt_bind_param($preparedStatement, "s", $categoryID); 
mysqli_stmt_execute($preparedStatement);
mysqli_stmt_bind_result($preparedStatement, $postTitle, $postContent, $usernamecol, $postDate, $postID);
 
echo"<table id = 'table-test'>";
echo"<tr>";
echo"<th>Title</th>";
echo"<th>Author</th>";
echo"<th>Post Date</th>";
echo"<th>Replies</th>";
echo"</tr>";

while(mysqli_stmt_fetch($preparedStatement)){
    $sql2 = "SELECT COUNT(ID) FROM replies WHERE replyPostId=$postID ORDER BY replyPostId";
    $result = mysqli_query($connection2, $sql2);

    while ($row = mysqli_fetch_assoc($result))
    {
    $tempcount = $row['COUNT(ID)'];
    }


    echo"<tr>";
    echo"<td><a href = 'forumReply.php?postID=$postID'>$postTitle</a></td>";
    echo"<td >$usernamecol</td>";
    echo"<td>$postDate</td>";
    echo"<td>$tempcount</td>";
    
    echo"</tr>";

}
mysqli_stmt_close($preparedStatement);
mysqli_free_result($result);


echo"</table>";

mysqli_close($connection);
mysqli_close($connection2);

if($categoryID == "1"){
    echo"<img src = 'images/thanos_simpson.jpeg'></img>";
}

?>

</body>
</html>
