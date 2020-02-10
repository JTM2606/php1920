<?php
session_start();
?>
<html>
<head>
<title>Men&uacute</title>
</head>
<body>
<?php

include "conexion.php";


if(isset($_POST['usuario'])){

	if (comprobarUsuario($conn)) {
		
		$_SESSION['usuario'] = $_POST['usuario'];
		
		
		echo "Usuario: ".$_POST['usuario'];
		
		echo "<p><a href='pe_altaped.php'>Alta Productos</a></p>";
		echo "<p><a href='.php'>Consulta de Compras</a></p>";
		
		
	}	
	}else{
	
		if(isset($_SESSION['usuario'])){
		echo "Has iniciado Sesion: ".$_SESSION['usuario'];
		
		echo "<p><a href='pe_altaped.php'>Alta Productos</a></p>";
		echo "<p><a href='.php'>Consulta de Compras</a></p>";
		
		}else{
			echo "Acceso Restringido debes hacer Login con tu usuario";
		}
	}
		

var_dump($_SESSION);

?>
<br /><a href="pe_login.php">Volver a pagina Login</a>
</body>
</html>

<?php

function comprobarUsuario($conn) {
	
	$resultado=true;
	
	try {
		
		
		// set the PDO error mode to exception
		
		$usuario=$_POST['usuario'];
		$clave=$_POST['clave'];
		$usu=null;
		$id=null;
		
		$stmt = $conn->prepare("SELECT * FROM ADMIN WHERE USERNAME='$usuario' AND PASSCODE='$clave'");
		$stmt->execute();
		
		foreach($stmt->fetchAll() as $row) {
			$usu=$row["id"];
		}
		
		if(empty($usu)) {
			$resultado=false;
			throw new PDOException('Cliente no Registrado<br>');
		} else {
			$stmt = $conn->prepare("SELECT * FROM ADMIN WHERE USERNAME='$usuario' AND PASSCODE='$clave'");
			$stmt->execute();
			foreach($stmt->fetchAll() as $row) {
				$id=$row["id"];
			}
			$_SESSION['id'] = $id;
			
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