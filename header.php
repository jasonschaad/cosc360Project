
<div id="header" class="closed">
	<button id="hamburger-toggle">
		<div class="bar1"></div>
		<div class="bar2"></div>
		<div class="bar3"></div>
	</button>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#hamburger-toggle").click(function() {
				$("#header").toggleClass(["closed", "open"]);
			})
		});
	</script>
	<nav>
		<a class="main" href="index.php">Home</a>
<?php 

if (!empty($_SESSION)) {
	if ($_SESSION["securityLevel"] > 0) {
		$tempName = $_SESSION["firstName"]." ".$_SESSION["lastName"];
		$tempuserID = $_SESSION["userID"]; 
		
		echo "<a class='menuItem' href='search.php'>Search</a>";
		if ($_SESSION["securityLevel"] == 2) {
			echo "<a class='menuItem' href='admin.php'>Admin</a>";
		}
		echo "<a class='welcomeMsg' href='edituser.php?ID=$tempuserID'>Welcome $tempName</a>";
		echo "<a class='menuItem' href='logout.php'>Logout</a>";
	}
	else {
		echo "<a class='menuItem' href='login.php'>Login</a>";
	}
} else {
	echo "<a class='menuItem' href='login.php'>Login</a>";
}
?>
	</nav>
</div>
