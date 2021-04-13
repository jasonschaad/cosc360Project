<?php
// Start the session
session_start();

$PAGENAME = "Search";
$HEAD = '
<script type="text/javascript" src="scripts/validate.js"></script>
';
$BREADCRUMB =  array(
  array("href" => "search.php", "name" => "Search")
);
include 'head.php';

echo "<p><a href='searchUser.php'>Search by User</a></p>";
echo "<p><a href='searchForum.php'>Search Forum</a></p>";

include 'foot.php';

?>
