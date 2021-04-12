<?php
// Start the session
session_start();

$HEAD = '
<script type="text/javascript" src="scripts/validate.js"></script>
';
include 'head.php';

// gets
if (!empty($_GET)) {
	$userID  = $_GET['userID'];
} 
else {
	$userID = "";
}

echo "<form id='main' method='get' action='searchUser.php'>";

/////////////////////////////////////////////
// begin searchbar (not much of a searchbar)
/////////////////////////////////////////////

// Build the form
echo "<fieldset>\n";
echo "<legend>Search User</legend>\n";

//make connection to database
include 'dbhosts.php';
$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null) {
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
} 

echo "<label for='user'>User:</label>\n";
echo "<select name='userID' id='userID'>\n";
$sql = "SELECT ID,username FROM users ORDER BY username ASC";
$result = mysqli_query($connection, $sql) or die ("Error in query. ".mysqli_error($connection));
$queryResult = mysqli_num_rows($result);

if ($queryResult > 0){
	while($row = mysqli_fetch_assoc($result)){
		$tempUserID = $row['ID'];
		$tempUsername = $row['username'];
		echo "<option value='$tempUserID'>$tempUsername</option>\n";
	}
}

echo "</select>\n";

echo "<br />\n";
echo "<br />\n";
echo "<input type='submit' value='Search'>";
echo "</fieldset>";
echo "</form>";

//////////////////////////////
// end of search bar
//////////////////////////////

if (!empty($userID)) {
	
	echo "<p>Checking Posts</p>";
	
	echo "<table id = 'table-test'>";
	echo "<tr>\n";
	echo "<th>Details</th>\n";
	echo "<th>Username</th>\n";
	echo "<th>Title</th>\n";
	echo"</tr>\n";
	
	// list posts first
	
	$sql = "SELECT posts.ID,title,username FROM posts
				JOIN users ON posts.postUserID = users.ID
				WHERE postUserID = ?";
	
	$preparedStatement = mysqli_prepare($connection, $sql);
	if ($preparedStatement === false) {
		die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
	}
	mysqli_stmt_bind_param($preparedStatement, "i", $userID); 
	mysqli_stmt_execute($preparedStatement);
	mysqli_stmt_bind_result($preparedStatement, $postsID, $title, $userName);
	
	while(mysqli_stmt_fetch($preparedStatement)){
		echo "<tr>\n";
		echo "<td><a href='forumReply.php?postID=$postsID'><img src='images/about24.png' alt='Go to Post' /></a></td>\n";
		echo "<td>$userName</td>\n";
		echo "<td>$title</td>\n";
		echo"</tr>\n";
	}
	
	// Close the statement
	mysqli_stmt_close($preparedStatement);
	
	echo"</table>";
		
	echo "<p>Checking Replies</p>";
	
	echo "<table id = 'table-test'>";
	echo "<tr>\n";
	echo "<th>Details</th>\n";
	echo "<th>Username</th>\n";
	echo "<th>Content</th>\n";
	echo"</tr>\n";
	
	// now replies
	
	$sql = "SELECT replyPostId,content,username FROM replies
				JOIN users ON replies.replyUserID = users.ID
				WHERE replyUserID = ?";
	
	$preparedStatement = mysqli_prepare($connection, $sql);
	if ($preparedStatement === false) {
		die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
	}
	mysqli_stmt_bind_param($preparedStatement, "i", $userID); 
	mysqli_stmt_execute($preparedStatement);
	mysqli_stmt_bind_result($preparedStatement, $postsID, $content, $userName);
	
	while(mysqli_stmt_fetch($preparedStatement)){
		echo "<tr>\n";
		echo "<td><a href='forumReply.php?postID=$postsID'><img src='images/about24.png' alt='Go to Post' /></a></td>\n";
		echo "<td>$userName</td>\n";
		echo "<td>$content</td>\n";
		echo"</tr>\n";
	}
	
	// Close the statement
	mysqli_stmt_close($preparedStatement);
	
	echo"</table>";
	
	// Close the database
	mysqli_close($connection);	
}

include 'foot.php';

?>
