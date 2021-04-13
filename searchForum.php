<?php
// Start the session
session_start();

$PAGENAME = "Search Forums";
$HEAD = '
<script type="text/javascript" src="scripts/validate.js"></script>
';
$BREADCRUMB =  array(
  array("href" => "search.php", "name" => "Search"),
	array("href" => "searchForum.php", "name" => "Search Forums")
);
include 'head.php';

// gets
if (!empty($_GET)) {
	$keyword  = $_GET['keyword'];
} 
else {
	$keyword = "";
}



echo "<form id='main' method='get' action='searchForum.php'>";

/////////////////////////////////////////////
// begin searchbar (not much of a searchbar)
/////////////////////////////////////////////

// Build the form
echo "<form method='post' action='edituser_process.php' id='mainForm'>\n";
echo "<fieldset>\n";
echo "<legend>Search Forum</legend>\n";
echo "<label for='username'>Keyword:</label>\n";
echo "<input type='text' name='keyword' id='keyword' value='$keyword'>\n";
echo "<br />\n";
echo "<br />\n";
echo "<input type='submit' value='Search'>";
echo "</fieldset>";
echo "</form>";

//////////////////////////////
// end of search bar
//////////////////////////////

//make connection to database
include 'dbhosts.php';
$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null) {
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
} 

if (!empty($keyword)) {
	
	echo "<p>Checking Posts</p>";
	
	// list posts first
	$param1 = "%$keyword%";
	$sql = "SELECT ID,title,postContent FROM posts
				WHERE postContent LIKE ?";
	
	$preparedStatement = mysqli_prepare($connection, $sql);
	if ($preparedStatement === false) {
		die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
	}
	mysqli_stmt_bind_param($preparedStatement, "s", $param1); 
	mysqli_stmt_execute($preparedStatement);
	mysqli_stmt_bind_result($preparedStatement, $postsID, $title, $content);
	
	// store result
	mysqli_stmt_store_result($preparedStatement);
	$num_rows = mysqli_stmt_num_rows($preparedStatement);
	
	if ($num_rows > 0) {
		echo "<table id = 'table-test'>";
		echo "<tr>\n";
		echo "<th>Details</th>\n";
		echo "<th>Title</th>\n";
		echo "<th>Content</th>\n";
		echo"</tr>\n";
		
	
		while(mysqli_stmt_fetch($preparedStatement)){
			echo "<tr>\n";
			echo "<td><a href='forumReply.php?postID=$postsID'><img src='images/about24.png' alt='Go to Post' /></a></td>\n";
			echo "<td>$title</td>\n";
			echo "<td>$content</td>\n";
			echo"</tr>\n";
		}
	}
	else {
		echo "<p>No posts were found.</p>\n";
	}
	
	// Close the statement
	mysqli_stmt_close($preparedStatement);
	
	echo"</table>";
		
	echo "<p>Checking Replies</p>";
	
	// now replies
	
	$param1 = "%$keyword%";
	$sql = "SELECT ID,Content FROM replies
				WHERE Content LIKE ?";
	
	$preparedStatement = mysqli_prepare($connection, $sql);
	if ($preparedStatement === false) {
		die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
	}
	mysqli_stmt_bind_param($preparedStatement, "s", $param1); 
	mysqli_stmt_execute($preparedStatement);
	mysqli_stmt_bind_result($preparedStatement, $postsID, $content);
	
	// store result
	mysqli_stmt_store_result($preparedStatement);
	$num_rows = mysqli_stmt_num_rows($preparedStatement);
	
	if ($num_rows > 0) {
		echo "<table id = 'table-test'>";
		echo "<tr>\n";
		echo "<th>Details</th>\n";
		echo "<th>Username</th>\n";
		echo "<th>Content</th>\n";
		echo"</tr>\n";	
	
		while(mysqli_stmt_fetch($preparedStatement)){
			echo "<tr>\n";
			echo "<td><a href='forumReply.php?postID=$postsID'><img src='images/about24.png' alt='Go to Post' /></a></td>\n";
			echo "<td>$content</td>\n";
			echo"</tr>\n";
		}
	}
	else {
		echo "<p>No replies were found.</p>\n";	
	}
	
	// Close the statement
	mysqli_stmt_close($preparedStatement);
	
	echo"</table>";
	
	// Close the database
	mysqli_close($connection);	
}

include 'foot.php';

?>
