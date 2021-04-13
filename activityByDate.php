<?php
// Start the session
session_start();
//# WILL DO MINOR CHANGES LATER, THIS WORKS FOR NOW
if(empty($_SESSION)){
    echo"you shouldn't be on this page!";
    die();
}
$PAGENAME = "Activity by Date";

$BREADCRUMB =  array(
    array("href" => "admin.php", "name" => "Admin"),
    array("href" => "activityByDate.php", "name" => "Activity By Date")
  );
include 'head.php';

$activityDate = $_POST["actDate"];

if(empty($activityDate)){
    echo"you didn't submit a date silly! try again";
    die();
}

include 'dbhosts.php';
$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null) {
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
} 
$sql = "SELECT posts.ID, username, title, categoryName FROM posts JOIN category ON postCategoryId = category.ID JOIN users ON posts.postUserId = users.ID WHERE DATE_FORMAT(postDate, '%Y-%m-%d')=?";
$time = strtotime($activityDate);
$formattedDate = date('F jS, Y', $time);
echo"<h3>Posts on $formattedDate</h3>";
echo"<table>";
echo"<tr>";
echo"<th>Post ID:</th>";
echo"<th>Username:</th>";
echo"<th>Post Title:</th>";
echo"<th>Category: </th>";
echo"</tr>";
$preparedStatement = mysqli_prepare($connection, $sql);
if ($preparedStatement === false) {
	die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
}
mysqli_stmt_bind_param($preparedStatement, "s", $activityDate); 
mysqli_stmt_execute($preparedStatement);
mysqli_stmt_bind_result($preparedStatement, $postID, $usernamecol, $title, $catName);
while(mysqli_stmt_fetch($preparedStatement)){
	echo"<tr>";
	echo"<td >$postID</td>";
	echo"<td >$usernamecol</td>";
	echo"<td >$title</td>";
	echo"<td >$catName</td>";
	echo"</tr>";
}

mysqli_stmt_close($preparedStatement);

// Close the database

echo"</table>";
echo"<br />";

echo"<h3>Replies on $formattedDate</h3>";
echo"<table>";
echo"<tr>";
echo"<th>Reply ID:</th>";
echo"<th>Username:</th>";
echo"<th>Reply Content:</th>";
echo"<th>Category:</th>";
echo"</tr>";
$sql = "SELECT replies.ID, username, content, categoryName FROM replies JOIN users ON replies.replyUserId = users.ID JOIN posts on replies.replyPostId = posts.ID JOIN category on posts.postCategoryId = category.ID WHERE DATE_FORMAT(replyDate, '%Y-%m-%d')=?";
$preparedStatement = mysqli_prepare($connection, $sql);
if ($preparedStatement === false) {
	die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
}
mysqli_stmt_bind_param($preparedStatement, "s", $activityDate); 
mysqli_stmt_execute($preparedStatement);
mysqli_stmt_bind_result($preparedStatement, $replyID, $usernamecol, $replyContent, $catName);


while(mysqli_stmt_fetch($preparedStatement)){
	echo"<tr>";
	echo"<td >$replyID</td>";
	echo"<td >$usernamecol</td>";
    echo"<td >$replyContent</td>";
    echo"<td >$catName</td>";
	echo"</tr>";
}
echo"</table>";

mysqli_stmt_close($preparedStatement);
mysqli_close($connection);


include 'foot.php';

?>
