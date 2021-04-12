<?php
// Start the session
session_start();

$HEAD = '
<script type="text/javascript" src="scripts/validate.js"></script>
';
include 'head.php';

echo "<p><a href='searchUser.php'>Search by User</a></p>";
echo "<p><a href='searchForum.php'>Search Forum</a></p>";

include 'foot.php';

?>
