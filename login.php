<?php
// Start the session
session_start();

$PAGENAME = 'Login';
$HEAD = '
<script type="text/javascript" src="scripts/validate.js"></script>
';
include 'head.php';
?>
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
  <a href="edituser.php">Create new account</a>
</div>

<?php

include 'foot.php';

?>
