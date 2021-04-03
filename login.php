<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Nerd Forum</title>
<script type="text/javascript" src="scripts/validate.js"></script>

</head>
<body>

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

</body>
</html>

