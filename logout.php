<?php
// Start the session
session_start();

// remove all session variables
session_unset();

// destroy the session
session_destroy();

include 'head.php';

echo "<p>You have successfully logged out. </p>\n";

include 'foot.php';

?>
