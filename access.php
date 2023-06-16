<?php 
  //starting session
  session_start();
  //checking if session is already saved and if not - redirect to log in site. 
  if (!isset($_SESSION['name'])) 
  {
    header("Location: /index.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Access</title>
  </head>
  <body>
    <?php
      // checking if session already saved and if yes, welcome user
      if (isset($_SESSION['name']))
      {
        $name = $_SESSION['name'];
        $password = $_SESSION['password'];

        echo "<p>Welcome back $name.<br>
              Your full name is $name.<br>
              and your password is '$password'.
              <a href='/logout.php'>Log out</a></p>";
      }
    ?>
  </body>
</html>