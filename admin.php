<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Nerd Forum</title>
<?php include ('head.php'); ?>

</head>
<body>
<?php 

// gets
if (!empty($_GET)) {
	$userNameSearch = $_GET['userNameSearch'];
	$firstNameSearch = $_GET['firstNameSearch'];
	$lastNameSearch = $_GET['lastNameSearch'];
	$emailSearch = $_GET['emailSearch'];
	
} 
else {
	$userNameSearch = "";
	$firstNameSearch = "";
	$lastNameSearch = "";
	$emailSearch = "";
}

include('header.php');

echo "<form id='main' method='get' action='admin.php'>";

/////////////////////////////////////////////
// begin searchbar (not much of a searchbar)
/////////////////////////////////////////////

// Build the form
echo "<form method='post' action='edituser_process.php' id='mainForm'>\n";
echo "<fieldset>\n";
echo "<legend>Filter Users</legend>\n";
echo "<label for='username'>Username:</label>\n";
echo "<input type='text' name='userNameSearch' id='userNameSearch' value='$userNameSearch'>\n";
echo "<br />\n";
echo "<label for='firstNameSearch'>First Name:</label>\n";
echo "<input type='text' name='firstNameSearch' id='firstNameSearch' value='$firstNameSearch'>\n";
echo "<br />\n";
echo "<label for='lastNameSearch'>Last Name:</label>\n";
echo "<input type='text' name='lastNameSearch' id='lastNameSearch' value='$lastNameSearch'>\n";
echo "<br />\n";
echo "<label for='emailSearch'>Email:</label>\n";
echo "<input type='text' name='emailSearch' id='emailSearch' value='$emailSearch'>\n";
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
echo "<th>First Name</th>\n";
echo "<th>Last Name</th>\n";
echo "<th>Email</th>\n";
echo"</tr>\n";

$param1 = "%$userNameSearch%";
$param2 = "%$firstNameSearch%";
$param3 = "%$lastNameSearch%";
$param4 = "%$emailSearch%";

$sql = "SELECT ID,username, firstName, lastName, email FROM users WHERE username LIKE ? AND firstName LIKE ? AND lastName LIKE ? and email LIKE ?";
$preparedStatement = mysqli_prepare($connection, $sql);
if ($preparedStatement === false) {
	die("prepare failed: " . htmlspecialchars(mysqli_error($connection)));
}
mysqli_stmt_bind_param($preparedStatement, "ssss", $param1, $param2, $param3, $param4); 
mysqli_stmt_execute($preparedStatement);
mysqli_stmt_bind_result($preparedStatement, $userID, $userName, $firstName, $lastName, $email);

while(mysqli_stmt_fetch($preparedStatement)){
	echo"<tr>";
	echo "<td><a href='edituser.php?ID=$userID'><img src='images/about24.png' alt='Details' /></a></td>\n";
	echo"<td >$userName</td>";
	echo"<td >$firstName</td>";
	echo"<td >$lastName</td>";
	echo"<td >$email</td>";
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

