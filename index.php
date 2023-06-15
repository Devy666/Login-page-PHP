<?php 
  // get log in informations
  require_once 'login.php';
  //create connection to database
  $connection =
    new mysqli($db_hostname, $db_username, $db_password, $db_database);

    //in case of unsucesfull connection send info about the connecting error
  if ($connection->connect_error) die($connection->connect_error);

  // checking if user has submitted username and password
  if (isset($_POST['name']) &&
      isset($_POST['password']))
  {
    //... and then secure input with sanitazing functions
    $un_temp = sanitizeMySQL($connection, $_POST['name']);
    $pw_temp = sanitizeMySQL($connection, $_POST['password']);

    //query for finding username in entire database
    $query  = "SELECT * FROM users WHERE name='$un_temp'";
    $result = $connection->query($query);
    //checking if there was no bug under the checking, otherwise sending msg
    if (!$result) die($connection->error);
    // if found in database...
    elseif ($result->num_rows)
    {
        //getting array with login information into an array 
        $row = $result->fetch_array(MYSQLI_NUM);
           //closing the connection 
          $result->close();
        // salt information
        $salt1 = "kRm!e";
        $salt2 = "p3ddD";
        $token = hash('ripemd128', "$salt1$pw_temp$salt2");

        //checking if secured password is the row array
        // if yes - redirecting to index side
        if ($token == $row[2]) {
          session_start();
          $_SESSION['name'] = $un_temp;
          $_SESSION['password'] = $pw_temp;
          header("Location: /index1.php");
        } 
          // if not - msg about mistake 
        else {
          die("Invalid username/password combination");
        }
    }
    // If username not found
    else die("Invalid username/password combination");
  }
  //If user didnt fill both username and password
  // else
  // {
  //   header('WWW-Authenticate: Basic realm="Restricted Section"');
  //   header('HTTP/1.0 401 Unauthorized');
  //   die ("Please enter your username and password");
  // }
  // ending connections
  $connection->close();

  // function to secure input
  function sanitizeString($var)
  {
    $var = stripslashes($var);
    $var = htmlentities($var);
    $var = strip_tags($var);
    return $var;
  }

  function sanitizeMySQL($connection, $var)
  { // Using the mysqli extension
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
    
  </body>
</html>

