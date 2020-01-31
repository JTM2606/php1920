<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>ALTA Clientes - JoseTorres</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */


if (!isset($_POST) || empty($_POST)) { 
	
	$NIFS=obtenerNIFS($conn);
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos</div>
<div class="card-body">
		<label for="nif">SELECCIONE SU NIF:</label>
		<select name="nif">
		<?php foreach($NIFS as $nif) : ?>
			<option> <?php echo $nif ?> </option>
		<?php endforeach; ?>
		</select>
		<div class="form-group">
	</BR>
<?php
	echo '<div><input type="submit" value="Registro Cliente"></div>
	</form>';
} else { 

	// Aquí va el código al pulsar submit
    try {
	
		
			// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		
		if ($_POST!=null) {
		
			if ($_POST['nif']!=null) {
				
				if (is_numeric(substr($_POST['nif'],0,8)) && ctype_alpha(substr($_POST['nif'],8))) {
					
					if (comprobarDNI($conn)) {
						altaCliente($conn);
					} else {
						throw new PDOException('NIF ERRONEO');
					}
				}
		
		
			
			} else {
			
		}
		}
	}
	catch(PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}
	
}
?>

<?php
// Funciones utilizadas en el programa

function altaCliente($conn) {
	
	try {
		
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$nif=$_POST['nif'];
		
		$nombre=null;
		$apellido=null;
		
		$stmt = $conn->prepare("SELECT NOMBRE,APELLIDO FROM cliente WHERE NIF='$nif'");
		$stmt->execute();
		
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$nombre=$row["NOMBRE"];
			$apellido=$row["APELLIDO"];
		}
		
		
		
		$clave=strrev($apellido);
		
		$sql = "INSERT INTO REGISTRO (NIF,NOMBRE,CLAVE) VALUES ('$nif','$nombre','$clave')";

		// use exec() because no results are returned
		$conn->exec($sql);
		echo "New record created successfully";
		
		
		
		}
		
	catch(PDOException $e)
		{
		echo $sql . "<br>" . $e->getMessage();
		}

	$conn = null;
	
}

function obtenerNIFS($conn) {
	
	try {
		$nif=array();
		$stmt = $conn->prepare("SELECT NIF FROM cliente");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$nif[]=$row["NIF"];
		}
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	
	$conn = null;
	
	
	
	return $nif;
	
	
}

function comprobarDNI($conn) {
	
	$resultado=true;
	
	try {
		
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$nif=$_POST['nif'];
		$nif_2=null;
		
		$stmt = $conn->prepare("SELECT NIF FROM REGISTRO WHERE NIF='$nif'");
		$stmt->execute();
		
		foreach($stmt->fetchAll() as $row) {
			$nif_2=$row["NIF"];
		}
		
		if($nif_2==$nif) {
			$resultado=false;
			throw new PDOException('Cliente con DNI Repetido');
		} else {
			
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



</body>

</html>