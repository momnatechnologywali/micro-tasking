<?php
$host = "localhost";
$dbname = "dbpzmrty1grvw4";
$username = "uws1gwyttyg2r";
$password = "k1tdlhq4qpsf";
 
$conn = new mysqli($host, $username, $password, $dbname);
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
