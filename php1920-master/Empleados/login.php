<?php

$servername = "localhost";
$username = "root";
$password = "rootroot";
$dbname="empleadosnn";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username, $password);
    // set the PDO error mode to exception
   $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully<br>"; 
	return $conn;
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }


?>