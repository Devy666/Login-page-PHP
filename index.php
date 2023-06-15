<?php 
  // get log in informations
  require_once 'login.php';
  $error_msg = null;
  //create connection to database
  $connection =
    new mysqli($db_hostname, $db_username, $db_password, $db_database);

  //in case of unsucesfull connection send info about the connecting error
  if ($connection->connect_error) die($connection->connect_error);

  // checking if user has submitted username and password with POST function
  if (isset($_POST['name']) &&
      isset($_POST['password']))
  {
    //... and then secure input with sanitazing functions
    $sent_username = sanitize($connection, $_POST['name']);
    $sent_password = sanitize($connection, $_POST['password']);

    //query for finding username in entire database
    $query  = "SELECT * FROM users WHERE name='$sent_username'";
    $result = $connection->query($query);
    //checking if there was no bug under the checking, otherwise sending msg
    if (!$result) die($connection->error);
    // if found in database...
    elseif ($result->num_rows)
    {
        //getting array with login information into an row array
        $row = $result->fetch_array(MYSQLI_NUM);
           //closing the connection 
          $result->close();
        // salt information
        $salt1 = "kRm!e";
        $salt2 = "p3ddD";
        $token = hash('ripemd128', "$salt1$sent_password$salt2");

        //checking if secured password is the row array
        // if yes - redirecting to index side
        if ($token == $row[2]) {
          // start session and save login information into session
          session_start();
          $_SESSION['name'] = $sent_username;
          $_SESSION['password'] = $sent_password;
          // redirekt to index1.php
          header("Location: /index1.php");
        } 
          // if not - msg about mistake 
        else {
          $error_msg = "Invalid username/password combination";
        }
    }
    // If username not found
    else {
      $error_msg = "Something went wrong. Please try again.";
    }
  }
  $connection->close();

  // functions to sanitate input
  function sanitizeString($var)
  {
    //removing slashes from a string
    $var = stripslashes($var);
    //changes html syntax to text
    $var = htmlentities($var);
    //removing tags from a string
    $var = strip_tags($var);
    return $var;
  }

  function sanitize($connection, $var)
  { 
    //removing escaping characters /n 
    $var = $connection->real_escape_string($var);
    $var = sanitizeString($var);
    return $var;
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
  </head>
  <body>
    <form method="post" action="index.php">
      What is your name?
      <input type="text" name="name" />
      Password:
      <input type="password" name="password" />
      <input type="submit" />
    </form>
    <?php if (isset($error_msg)) {
      echo "<p>$error_msg</p>";
    }
    ?>
  </body>
</html>

