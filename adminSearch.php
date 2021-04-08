<?php

$host = "localhost";
$database = "nerdForum";
$user = "webuser";
$password = "P@ssw0rd";
$connection = new mysqli($host, $user, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
  }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST["search"];

}
else{
    echo "Incorrect request_method, please use POST";
    die();
}

if(isset($_POST['submit-search'])){
    $sql = "SELECT * FROM users JOIN posts ON posts.postUserId=users.ID  WHERE username LIKE '%$search%' OR email LIKE '%$search%' OR title LIKE '%$search%' OR postContent LIKE '%$search%'";
    $result = mysqli_query($connection, $sql); 


    $queryResult = mysqli_num_rows($result);

    if($queryResult > 0){
        while($row = mysqli_fetch_assoc($result)){
            echo "<div class = 'results'>
            <p>".$row['username']."</p>
            <p>.".$row['email']."</p>
            <p>".$row['title']."</p>
            <p>".$row['postContent']."</p>
            </div> ";
        }

    }
    else{
        echo "There are no results matching your search.";
    }
}

?>