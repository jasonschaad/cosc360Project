<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Nerd Forum</title>
</head>
<body>

<?php
// remove all session variables
session_unset();

// destroy the session
session_destroy();

include 'header.php';

echo "<p>You have successfully logged out. </p>\n";

?>


</body>
</html>