<?php
//get login information from login.php
  require_once 'login.php';
  //create connection to database
  $connection =
    new mysqli($db_hostname, $db_username, $db_password, $db_database);
  //in case of unsucesfull connection send info about the connecting error
  if ($connection->connect_error) die ($connection->connect_error);
  //query for creating a table with id, name and password
  $query_table = "CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(32) NOT NULL,
    password VARCHAR(64) NOT NULL,
    PRIMARY KEY (id)
  )";

  //sending the query to database and checking if it's going through
  $result = $connection->query($query_table);
  if (!$result) die ($connection->error);
  //creating two salt variables
  $salt1 = "kRm!e";
  $salt2 = "p3ddD";
  
  // Creating first user
  $name = 'Bella';
  $password = '12345!';
  //securing first password with hash and salt functions
  $secure_pass = hash('ripemd128', "$salt1$password$salt2");
  //using new_user function to create new user
  new_user($connection, $name, $secure_pass);

  //creating second user
  $name = 'Anna';
  $password = '54321!';
  $secure_pass = hash('ripemd128', "$salt1$password$salt2");
  new_user($connection, $name, $secure_pass);

  //functions for adding new user to a table
  function new_user($connection, $name, $pass)
  {
    //query to database
    $query_user  = "INSERT INTO users VALUES(NULL, '$name', '$pass')";
    //checking if succesfully created
    $result = $connection->query($query_user);
    if (!$result) die ($connection->error);
  }
?>