<?php
$servername = "sql11.freemysqlhosting.net";
$username = "sql11496494";
$password = "psppHndwlA";
$database = "sql11496494";

try {
    $connection = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully!";
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
?>