<?php

require "header.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<main>

<?php
  if(isset($_GET['error'])){
    if($_GET['error'] == "emptyfields"){
      echo '<p>Please fill in all fields</p>';
    }
    else if($_GET['error'] == "invalidmail") {
        echo '<p>Please enter a valid email</p>';
    }
  }
?>

<!-- <div class="topBar">
  <h1 id="brand">FileCoop</h1>
  <a class="loginLink" href="login.php">Log-In</a>
</div> -->

<form class="center" action="includes/signup.inc.php" method="post">
<h1>Create Account</h1>
<input type="text" name="fName" placeholder="First Name"/>
<input type="text" name="sName" placeholder="Surname"/>
<input type="text" name="uEmail" placeholder="Email"/>
<input type="password" name="pwd" placeholder="Password"/>
<input type="password" name="pwd-repeat" placeholder="Repeat Password"/>
<button type="submit" name="signup-submit">Submit</button>
<a href="index.php">Sign-In</a>
</form>

</main>

<?php

require "footer.php";

?>
