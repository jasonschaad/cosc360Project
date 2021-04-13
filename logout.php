<?php
// Start the session
session_start();

// remove all session variables
session_unset();

// destroy the session
session_destroy();

$PAGENAME = "Logged Out";
include 'head.php';

echo "<p>You have successfully logged out. </p>\n";

include 'foot.php';

?>
