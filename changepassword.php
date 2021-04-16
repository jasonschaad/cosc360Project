<?php
// Start the session
session_start();

$PAGENAME = "Change Password";
$HEAD = '
<script type="text/javascript" src="scripts/validate.js"></script>

<script type="text/javascript">

function checkPasswordMatch(e) {
  var password = document.getElementById("newpassword");
  var passwordcheck = document.getElementById("newpassword2");
  if (password.value != passwordcheck.value) {
    $("#statusText").html("Passwords must be the same. Please try again.");
    makeRed(password);
    makeRed(passwordcheck);
    e.preventDefault();
  }
}

function onChangePasswordRejected() {
  makeRed(document.getElementById("oldpassword"));
  $("#statustext").html("Error: Old password is incorrect.");
}
function onChangePasswordSuccess() {
  makeClean(document.getElementById("oldpassword"));
  $("#statustext").html("Success! Your old password is correct.");
}

$(function() {
  // fetch from DOM
  var OldPassword = document.getElementById("oldpassword").value;
  $("#oldpassword").keydown(function() {
    makeClean(this);
  });

  $("#oldpassword").blur(function(){
    // fetch from DOM
    var OldPassword = document.getElementById("oldpassword").value;
    $.ajax({
      url: "changepassword_ajax.php",
      async: true,
      data: {
        OldPassword: OldPassword
      },
      type: "POST",
      context: document.body
    }).done(function(data) {
        console.log(data);
        if (data == "Match") {
          onChangePasswordSuccess();
        }
        else {
          onChangePasswordRejected();
        }
    });
  });
});

</script>
';
$BREADCRUMB =  array(
  array("href" => "changepassword.php", "name" => "Change Password")
);

include 'head.php';



$userID = $_SESSION['userID']; 

$isProblem = 0 ;

if (!is_numeric($userID)) {
  $isProblem = 1; 
}

if (empty($userID)) {
  $isProblem = 1; 
}

if ($isProblem) {
  $output = "<p>One of the required GET variables is not as expected.</p>";
  $output .= "<p><a href='edituser.php'>Return to the edit user page</a></p>";
  exit($output);
}

// All good we can connect to database now
include 'dbhosts.php';
  
// Build the form
echo "<form method='post' action='changepassword_process.php' id='mainForm'>\n";
echo "<fieldset>\n";
echo "<legend>Change Password</legend>\n";

echo "<label for='oldpassword'>Old password:</label>\n";
echo "<input type='password' name='oldpassword' id='oldpassword' class='required'/> \n";
echo "<br />\n";

echo "<label for='newpassword'>New password:</label>\n";
echo "<input type='password' name='newpassword' id='newpassword' class='required' /> \n";
echo "<br />\n";

echo "<label for='newpassword2'>Retype new password:</label>\n";
echo "<input type='password' name='newpassword2' id='newpassword2' class='required'/> \n";

echo "<br /><p id='statustext'></p><br />";
echo "<input type='submit' value='Change Password'>";
echo "</fieldset>";
echo "</form>";

include 'foot.php';

?>
