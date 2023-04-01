<?php

include $_SERVER['DOCUMENT_ROOT'].'/Helper/DotEnv.php';
(new DotEnv($_SERVER['DOCUMENT_ROOT'].'/.env'))->load();

$host = 'db';
$user = 'root';
$pass = getenv('MYSQL_ROOT_PASSWORD');

// check the MySQL connection status
$conn = new mysqli($host, $user, $pass, 'cp476');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  // echo "Connected successfully<br>"; 
}
?>