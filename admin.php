<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Nerd Forum</title>
<?php include ('head.php'); ?>
<script type="text/javascript" src="scripts/validate.js"></script>


</head>
<body>
<?php 

// gets
if (!empty($_GET)) {
	$userNameSearch = $_GET['userNameSearch'];
} 
else {
	$userNameSearch = "";
}

include('header.php');

echo "<form id='main' method='get' action='admin.php' class='validate'>";

/////////////////////////////////////////////
// begin searchbar (not much of a searchbar)
/////////////////////////////////////////////

// Build the form
echo "<form method='post' action='edituser_process.php' id='mainForm'>\n";
echo "<fieldset>\n";
echo "<legend>Filter Users</legend>\n";
echo "<label for='username'>Username:</label>\n";
echo "<input type='text' name='userNameSearch' id='userNameSearch' class='required' value='$userNameSearch'>\n";
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

echo "<table id = 'table-test'>";
echo "<tr>\n";
echo "<th>Details</th>\n";
echo "<th>Username</th>\n";
echo"</tr>\n";

$param = "%$userNameSearch%";
$sql = "SELECT ID,username FROM users WHERE username LIKE ?";
$preparedStatement = mysqli_prepare($connection, $sql);
if ($preparedStatement === false) {
	die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
}
mysqli_stmt_bind_param($preparedStatement, "s", $param); 
mysqli_stmt_execute($preparedStatement);
mysqli_stmt_bind_result($preparedStatement, $userID, $userName);

while(mysqli_stmt_fetch($preparedStatement)){
	echo"<tr>";
	echo "<td><a href='edituser.php?ID=$userID'><img src='images/about24.png' alt='Details' /></a></td>\n";
	echo"<td >$userName</td>";
	echo"</tr>";
}

// Close the statement
mysqli_stmt_close($preparedStatement);

// Close the database
 mysqli_close($connection);

echo"</table>";

?>

</body>
</html>

