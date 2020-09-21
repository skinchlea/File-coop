<?php

require "header.php";

if(isset($_SESSION['userID'])){
  //IS LOGGED IN


} else {
      //IS LOGGED OUT
      echo '<div class="center">
      <h1>Log In</h1><form action="includes/login.inc.php" method="post">
      <input type="text" name="uname" placeholder="Email"/>
      <input type="password" name="pwd" placeholder="Password"/>
      <button type="submit" name="login-submit">Login</button>
      <a href="signup.php">Create Account</a>
      </form>
      </div>';

}
 ?>
