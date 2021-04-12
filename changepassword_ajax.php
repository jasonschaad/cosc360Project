<?php 
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$isAuthorized = 0;

if (isset($_SESSION['securityLevel']) && $_SESSION['securityLevel'] >= 1) {
	$isAuthorized = 1;
}

if (!$isAuthorized) {
	// not authorized to view this page
	echo "There is nothing to see here. Move along.";
	return;
}

include 'dbhosts.php';

$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null) {
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
} 
else {
	$OldPassword = $_POST['OldPassword'];
	
	// $OldPassword = "password";
	// $NewPassword = "rawr";

	// exit conditions
	if ($OldPassword == '') {
		$output = "<p>Old password must not be blank.</p>";
		$output .= "<p><a href='changepassword.php'>Return to change password</a></p>";
		mysqli_close($connection);
		exit($output);
	}

	$query = "SELECT 
				password 
				FROM users 
				WHERE 
				ID = '".$_SESSION['userID']."' 
				limit 1";

	$result = mysqli_query($connection,$query) or die ("Error in query: $query. ".mysqli_error($connection)); 
	$number_of_rows = mysqli_num_rows($result);

	// exit if no rows found
	if ($number_of_rows == 0) { 
		$output = "<p>No match found.</p>";
		$output .= "<p><a href='changepassword.php'>Return to change password</a></p>";
		mysqli_close($connection);
		exit($output);
	}

	$row = mysqli_fetch_assoc($result);
	$PasswordHash = $row['password'];
	
	$OldPasswordHash = md5($OldPassword);
	
	if ($OldPasswordHash == $PasswordHash) {
		echo "Match";
	}
	else {
		echo "No Match";
		return;
	}
}

?>
