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
<?php include('header.php');?>

all default passwords are "password" (no quotes)
login is your first name all lowercase

<form method="post" action="login_process.php" id="mainForm" >
  Username:<br>
  <input type="text" name="username" id="username" class="required">
  <br>
  Password:<br>
  <input type="password" name="password" id="password" class="required">
  <br>
  <br><br>
  <input type="submit" value="Login">
</form>

<br />

<div id="forgotPassword">
  <a href="forgotPassword.php">Forgot your password?</a>
</div>

<div id="createNewAccount">
  <a href="url">Create new account</a>
</div>

</body>
</html>

