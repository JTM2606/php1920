<?php
/* Conexión BD */
/*define('DB_SERVER', '10.129.15.249');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'rootroot');
define('DB_DATABASE', 'comprasweb');
$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
   
   if (!$conn) {
		die("Error conexión: " . mysqli_connect_error());
	}*/
	
$servername = "10.129.15.249";
$username = "root";
$password = "rootroot";
$dbname="comprasweb";
	
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
