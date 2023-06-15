<?php
  session_start();

  if (isset($_SESSION['name']))
  {
    $name = $_SESSION['name'];
    $password = $_SESSION['password'];

    echo "Welcome back $name.<br>
          Your full name is $name.<br>
          and your password is '$password'.";
  }
  else echo "Please <a href='index.php'>click here</a> to log in.";
?>