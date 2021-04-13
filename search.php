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

echo "<a href='searchUser.php' class='button'>Search by User</a>";
echo "<br />";
echo "<a href='searchForum.php' class='button'>Search Forum</a>";

include 'foot.php';

?>
