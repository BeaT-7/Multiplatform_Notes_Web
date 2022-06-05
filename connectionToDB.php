<?php
$servername = "sql11.freemysqlhosting.net";
$databaseUsername = "sql11496494";
$databasePassword = "psppHndwlA";
$database = "sql11496494";

try {
    $connection = new PDO("mysql:host=$servername;dbname=$database", $databaseUsername, $databasePassword);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}



?>