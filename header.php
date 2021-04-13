
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
		<a class="main computerFont" href="index.php">Nerd Forum</a>
<?php 
echo "<a class='menuItem' href='search.php'>Search</a>";
if (!empty($_SESSION)) {
	if (isset($_SESSION['securityLevel']) && $_SESSION["securityLevel"] > 0) {
		$tempName = $_SESSION["firstName"]." ".$_SESSION["lastName"];
		$tempuserID = $_SESSION["userID"]; 
		
		
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
<div id="breadcrumb">
	<a href="index.php">&#x1F3E0; Home</a>
<?php if (isset($BREADCRUMB) && is_array($BREADCRUMB)) {
	for ($i = 0; $i < count($BREADCRUMB); $i++) {
		$item = $BREADCRUMB[$i];
		if (isset($item['href'])) {
			echo "\t<a href='".$item['href']."'>".$item['name']."</a>\n";
		} else {
			echo "\t<p>".$item['name']."</p>\n";
		}
	}
}?>
</div>
