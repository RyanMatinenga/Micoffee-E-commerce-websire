<?php

$conn = mysqli_connect('localhost','root','','shop_db') or die('connection failed');

$mysqli = new mysqli('localhost', 'root', '', 'shop_db');
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
return $mysqli;


?>