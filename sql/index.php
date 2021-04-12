<?php
// as an easy way to prevent users from browsing server folders without using .htaccess
$parent = dirname($_SERVER['REQUEST_URI']);
header("Location: $parent/index.php");
exit();
?>
