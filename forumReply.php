<?php
// Start the session
session_start();

$HEAD = '
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#hide").click(function(){
                $("#hide").html($("#hide").html().substr(0, 1) == "C" ? "Show Replies" : "Collapse Replies");
                $("#replies_collapse").fadeToggle();
            });
        });
    </script>
';

    include 'dbhosts.php';
    $connection = mysqli_connect($host, $user, $password, $database);

    $error = mysqli_connect_error();
    if($error != null) {
    $output = "<p>Unable to connect to database!</p>";
    exit($output);
    } 
    $postId = $_GET["postID"];
    // echo "=> $postId <==";
    // commented out to avoid errors for unauthenticated users. Also this wasn't being used anyways.
    // $sessionuserID = $_SESSION["userID"];
    $nocache = rand(1,9999);
    $dir = "files";
    
    $sql = "SELECT categoryName, category.ID AS categoryID, posts.title AS title, posts.ID FROM category LEFT JOIN posts ON postCategoryId = category.ID WHERE posts.ID = ?";
    $preparedStatement = mysqli_prepare($connection, $sql);
    if ($preparedStatement === false) {
        die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
    }
    //$categoryName = "Posts";
    //$categoryID = 1;
    //$postTitle = "Some Post";
    //$throwaway = 1;
    mysqli_stmt_bind_param($preparedStatement, "i", $postId); 
    mysqli_stmt_execute($preparedStatement);
    mysqli_stmt_bind_result($preparedStatement, $categoryName, $categoryID, $postTitle, $throwaway);
    mysqli_stmt_fetch($preparedStatement);
    
    // echo "cN ==>$categoryName <===";
    
    mysqli_stmt_close($preparedStatement);
    $PAGENAME = "$categoryName";
    $BREADCRUMB = array(
        array("name" => "Categories"),
        array("href" => "forumPost.php?categoryID=$categoryID", "name" => $categoryName),
        array("name" => "Posts"),
        array("href" => "forumReply.php?postID=$postId", "name" => $postTitle),
    );

    include 'head.php';


    $sql = "SELECT title, DATE_FORMAT(postDate, '%M %e, %Y %l:%i %p') as formattedDate, postContent, users.username AS userName, users.ID FROM posts JOIN users ON postUserId = users.ID WHERE posts.ID = ?";
    $preparedStatement = mysqli_prepare($connection, $sql);
    if ($preparedStatement === false) {
        die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
    }
    echo"<button id = 'hide'>Collapse Replies</button>";
    mysqli_stmt_bind_param($preparedStatement, "s", $postId); 
    mysqli_stmt_execute($preparedStatement);
    mysqli_stmt_bind_result($preparedStatement, $postTitle, $postDate, $postContent, $author, $postUserId);
    echo"<div class = 'placeholder-post-container'>";
    while (mysqli_stmt_fetch($preparedStatement)){
        echo"<article class = 'placeholder-user-info'>";
        echo"<i>Posted on $postDate</i>";
        echo"<div class='avatar'>";
        if (file_exists("{$dir}/"."$postUserId.jpg")) {
            echo "<img src='files/$postUserId.jpg?$nocache' alt='photo' width='96px' height = '125px' />";
        } else {
            echo "<img src='images/anonymous.png?$nocache' alt='photo'  />\n ";
        }
        echo"</div>";
        echo "<p class='author'>$author</p>";
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

    $sql = "SELECT content, DATE_FORMAT(replyDate, '%M %e, %Y %l:%i %p'), users.username AS userName, replies.ID, replyPostId, users.ID FROM replies JOIN users ON replyUserId = users.ID WHERE replyPostid = ?";
    $preparedStatement = mysqli_prepare($connection, $sql);
    if ($preparedStatement === false) {
        die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
    }
    mysqli_stmt_bind_param($preparedStatement, "s", $postId); 
    mysqli_stmt_execute($preparedStatement);
    mysqli_stmt_bind_result($preparedStatement, $replyContent, $replyDate, $author, $replyID, $replyPostID,$usersID);
    echo"<div id = 'replies_collapse'>";
   
    while (mysqli_stmt_fetch($preparedStatement)){
        echo"<div class = 'placeholder-post-container'>";
        echo"<article class = 'placeholder-user-info'>";
        echo"<i>Posted on $replyDate</i>";
        echo"<div class='avatar'>";
        if (file_exists("{$dir}/"."$usersID.jpg")) {
            echo "<img src='files/$usersID.jpg?$nocache' alt='photo' width='96px' height = '125px' />";
        } else {
            echo "<img src='images/anonymous.png?$nocache' alt='photo'  />\n ";
        }
        echo"</div>";
        echo "<p class='author'>$author</p>";
        //echo"<figure><img src = '$postID.jpg'></img></figure>";
        echo"</article>";
        echo"<article class = 'placeholder-main-content'>";
        echo "$replyContent";
        echo"</article>";
        if(isset($_SESSION['securityLevel']) && $_SESSION["securityLevel"]== 2){
            echo"<td><button><a href = 'deleteReply_process.php?&replyID=$replyID&postID=$replyPostID'>Remove Reply</a></button></td>";
        }
        echo"</div>";
    }
    mysqli_stmt_close($preparedStatement);
    echo"</div>";
    //##MODIFY CODE LATER - add if statement to check if users are logged in (Security level > 0).

    //ensure this code only shows up for users (securitylevel ==1) or admins (securitylevel ==2)
    if(isset($_SESSION['securityLevel']) && ($_SESSION["securityLevel"]== 1 || $_SESSION["securityLevel"] == 2)){

        echo "<form method='post' action='forumReply_process.php' id='replyForm' enctype='multipart/form-data'>\n";
        echo "<fieldset>\n";
        echo "<legend>Reply to this thread</legend>\n";
        echo "<label for='replyContent'>Post Reply: </label>\n";
        echo "<textarea id = 'replyContent' name = 'replyContent' placeholder = 'Write your reply here!' rows = '3' cols = '50'>\n";
        echo "</textarea>";
        echo "<input type='hidden' id='postID' name='postID' value='$postId'>\n";
        echo"<input type = 'submit' value = 'Submit Reply'>";
        echo "</fieldset>";
        echo "</form>";

    }

mysqli_close($connection);

include 'foot.php';

?>
