<?php
// Start the session
session_start();

$PAGENAME = 'Posts';
$HEAD = '
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $("#hide").click(function(){
            $("#hide").html($("#hide").html().substr(0, 1) == "C" ? "Show Posts" : "Collapse Posts");
            $("#posts_collapse").fadeToggle();
        });
    });
</script>
';

$categoryID = $_GET["categoryID"];



include 'dbhosts.php';

$connection = mysqli_connect($host, $user, $password, $database);
$connection2 = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null) {
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
} 
$sql = "SELECT categoryName FROM category WHERE ID = ?";
$preparedStatement = mysqli_prepare($connection, $sql);
if ($preparedStatement === false) {
    die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
}
$categoryName = "Posts";
mysqli_stmt_bind_param($preparedStatement, "s", $categoryID); 
mysqli_stmt_execute($preparedStatement);
mysqli_stmt_bind_result($preparedStatement, $categoryName);
mysqli_stmt_fetch($preparedStatement);
mysqli_stmt_close($preparedStatement);
$PAGENAME = "$categoryName";
$BREADCRUMB = array(
    array("name" => "Categories"),
    array("href" => "forumPost.php?categoryID=$categoryID", "name" => "$categoryName")
);

include 'head.php';

$sql = "SELECT title, postContent, users.username, DATE_FORMAT(postDate, '%M %e, %Y %l:%i %p'), posts.ID FROM posts JOIN users ON postUserId = users.ID WHERE postCategoryId = ?";
$preparedStatement = mysqli_prepare($connection, $sql);
if ($preparedStatement === false) {
    die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
}
mysqli_stmt_bind_param($preparedStatement, "s", $categoryID); 
mysqli_stmt_execute($preparedStatement);
mysqli_stmt_bind_result($preparedStatement, $postTitle, $postContent, $usernamecol, $postDate, $postID);
echo"<button id = 'hide'>Collapse Posts</button>";
echo"<div id = 'posts_collapse'>";
echo"<table id = 'table-test'>";
echo"<tr>";
echo"<th>Title</th>";
echo"<th>Author</th>";
echo"<th>Post Date</th>";
echo"<th>Replies</th>";
if(isset($_SESSION['securityLevel']) && $_SESSION["securityLevel"] == 2){
    echo"<th>Admin</th>";
}
echo"</tr>";


while(mysqli_stmt_fetch($preparedStatement)) {
    $sql2 = "SELECT COUNT(ID) AS myCount FROM replies WHERE replyPostId=$postID ORDER BY replyPostId";
    $result = mysqli_query($connection2, $sql2);

    while ($row = mysqli_fetch_assoc($result)) {
      $tempcount = $row['myCount'];
    }

    echo"<tr>";
    echo"<td><a href = 'forumReply.php?postID=$postID'>$postTitle</a></td>";
    echo"<td >$usernamecol</td>";
    echo"<td>$postDate</td>";
    echo"<td>$tempcount</td>";
    if(isset($_SESSION['securityLevel']) && $_SESSION["securityLevel"] == 2){
        // Edit
        echo"<td>\n";
        echo "<button><a href = 'editPost.php?postID=$postID&categoryID=$categoryID'>Edit</a></button>\n";
        // Remove
        echo"<button><a href = 'deletePost_process.php?postID=$postID&categoryID=$categoryID'>Remove</a></button>\n";
        echo "</td>";
       
    }
    
    echo"</tr>";

}
mysqli_stmt_close($preparedStatement);
mysqli_free_result($result);


echo"</table>";
echo"</div>";

mysqli_close($connection);
mysqli_close($connection2);

if(isset($_SESSION['securityLevel']) && ($_SESSION["securityLevel"]== 1 || $_SESSION["securityLevel"] == 2)){

    echo "<form method='post' action='forumPost_process.php' id='postForm' enctype='multipart/form-data'>\n";
    echo "<fieldset>\n";
    echo "<legend>Create a new Post</legend>\n";
    echo "<label for='newpostTitle'>Post Title: </label>\n";
    echo "<textarea id = 'newpostTitle' name = 'newpostTitle' placeholder = 'Write your title here' rows = '1' cols = '50'>\n";
    echo "</textarea>";
    echo "<label for='newpostContent'>Post Content: </label>\n";
    echo "<textarea id = 'newpostContent' name = 'newpostContent' placeholder = 'Write your post here' rows = '3' cols = '50'>\n";
    echo "</textarea>";
    echo "<input type='hidden' id='categoryID' name='categoryID' value='$categoryID'>\n";
    echo"<input type = 'submit' value = 'Create Post'>";
    echo "</fieldset>";
    echo "</form>";

}

include 'foot.php';

?>
