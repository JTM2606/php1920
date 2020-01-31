<?php
session_start();
?>
<html>
<head>
<title>Pagina 2</title>
</head>
<body>
<?php

include "conexion.php";


if(isset($_POST['usuario'])){

	if (comprobarUsuario($conn)) {
		
		$_SESSION['usuario'] = $_POST['usuario'];
		echo "Usuario: ".$_POST['usuario'];
		
		echo "<p><a href='compro2.php'>Compra de Productos</a></p>";
		echo "<p><a href='comconscom.php'>Consulta de Compras</a></p>";
		
		
	}	
	}else{
	
		if(isset($_SESSION['usuario'])){
		echo "Has iniciado Sesion: ".$_SESSION['usuario'];
		
		echo "<p><a href='compro2.php'>Compra de Productos</a></p>";
		echo "<p><a href='comconscom.php'>Consulta de Compras</a></p>";
		
		}else{
			echo "Acceso Restringido debes hacer Login con tu usuario";
		}
	}
		

var_dump($_SESSION);

?>
<br /><a href="login.php">Volver a pagina Login</a>
</body>
</html>

<?php

function comprobarUsuario($conn) {
	
	$resultado=true;
	
	try {
		
		
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$usuario=$_POST['usuario'];
		$clave=$_POST['clave'];
		$usu=null;
		
		$stmt = $conn->prepare("SELECT * FROM REGISTRO WHERE NOMBRE='$usuario' AND CLAVE='$clave'");
		$stmt->execute();
		
		foreach($stmt->fetchAll() as $row) {
			$usu=$row["NIF"];
		}
		
		if(empty($usu)) {
			$resultado=false;
			throw new PDOException('Cliente no Registrado<br>');
		} else {
			//$_SESSION["$usuario"]=array();
		}
		
		
		}
		
	catch(PDOException $e)
		{
		echo "Error: " . $e->getMessage();
		}

	$conn = null;
	
	return $resultado;
}


?>