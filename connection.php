<?php

$conn = new mysqli('localhost', 'root', 'passw0rd', 'birthday_reminder');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>