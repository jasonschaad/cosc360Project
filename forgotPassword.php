<?php
// Start the session
session_start();

$PAGENAME = 'Forgot Password';
$HEAD = '
<script type="text/javascript" src="scripts/validate.js"></script>
';
$BREADCRUMB =  array(
  array("href" => "login.php", "name" => "Login"),
  array("href" => "forgotPassword.php", "name" => "Forgot Password")
);

include 'head.php';


?>
<p>To reset your password please enter your email address.</p>

<form method="post" action="forgotPassword_process.php" id="mainForm" >
  Email:<br />
  <input type="text" name="email" id="email" class="required">
  <br />
  <br /><br />
  <input type="submit" value="Reset Password">
</form>

<?php
include 'foot.php';
?>
