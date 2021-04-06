<?php
// Start the session
session_start();
?>
<!DOCTYPE html>

<html>
<head>
    <style>
        /* added temporary styles for visualization :) */
        #table-test td{border: 1px solid black;}
        #table-test{border: 1px solid black; margin-left: auto; margin-right: auto; width: 80%; text-align: center;}
        h1 {text-align: center;}
        
    </style>
<title>Nerd Forum</title>
</head>
<body>

<?php

include 'header.php';

echo "<h1>Welcome to NerdForum</h1>";
//creating table to display all post categories.
echo"<table id = 'table-test'>";
echo"<tr>";
echo"<th>Categories</th>";
echo"</tr>";
//make connection to database
include 'dbhosts.php';
$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null) {
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
} 
//continue doing things if no error.
$sql = "SELECT ID,categoryName from category";
$result = mysqli_query($connection, $sql);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo"<tr>";
        $tempCategory = $row["categoryName"];
        $tempID= $row["ID"];
        //echo link with categoryID to forumPost.php
        echo"<td><a href='forumPost.php?categoryID=$tempID'</a>$tempCategory</td>";
        echo"</tr>";
    }
}
echo"</table>";
//close connection.

mysqli_close($connection);

?>


</body>
</html>