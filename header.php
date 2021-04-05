<?php

if (!empty($_SESSION)) {
	if ($_SESSION["securityLevel"] > 0) {
		$tempName = $_SESSION["firstName"]." ".$_SESSION["lastName"];
		echo "<a href='index.php'>Home</a>";
		echo " | ";
		echo "<a href='edituser.php'>Welcome $tempName</a>";
		echo " | ";
		echo "<a href='logout.php'>Logout</a>";
	}
	else {
		echo "<a href='login.php'>Login</a>";
	}
} else {
	echo "<a href='index.php'>Home</a>";
	echo " | ";
	echo "<a href='login.php'>Login</a>";
}

?>
