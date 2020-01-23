<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>Comprobar Compras - JoseTorres</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
$nifs=obtenerNifs($conn);

if (!isset($_POST) || empty($_POST)) { 

	
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-body">
	<div class="form-group">
	<label for="nif">NIF:</label>
	<select name="nif">
		<?php foreach($nifs as $nif) : ?>
			<option> <?php echo $nif ?> </option>
		<?php endforeach; ?>
	</select>
	</div>
	<div class="form-group">
        Desde <input type="date" name="fecha_i" class="form-control">
    </div>
	<div class="form-group">
        Hasta <input type="date" name="fecha_d" class="form-control">
    </div>
</div>
	</BR>
<?php
	echo '<div><input type="submit" value="Consultar"></div>
	</form>';
} else { 

	// Aquí va el código al pulsar submit
    try {
	
		
			// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		
		if ($_POST!=null) {
		
			listarCompras($conn);
		} else {
		
		}
    }
	catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
	
}
?>

<?php
// Funciones utilizadas en el programa

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

function listarCompras($conn) {
	
	try {
		
		$fecha_i=$_POST['fecha_i'];
		$fecha_d=$_POST['fecha_d'];
		if ($fecha_i>$fecha_d) {
			
			throw new PDOException('La fecha Desde es mayor que la fecha Hasta, IMPOSIBLE');			
		}
	
	
		$nif=$_POST['nif'];
		
		
		
		/*$stmt = $conn->prepare("SELECT producto.ID_PRODUCTO,producto.NOMBRE,producto.PRECIO,compra.UNIDADES,compra.FECHA_COMPRA
							FROM producto,compra WHERE producto.ID_PRODUCTO=compra.ID_PRODUCTO AND compra.NIF=$nif AND FECHA_COMPRA>=$fecha_i AND FECHA_COMPRA<=$fecha_d");
		*/
		$stmt = $conn->prepare("SELECT producto.ID_PRODUCTO,producto.NOMBRE,producto.PRECIO,compra.UNIDADES,compra.FECHA_COMPRA
							FROM producto,compra WHERE producto.ID_PRODUCTO=compra.ID_PRODUCTO AND compra.NIF='$nif' AND FECHA_COMPRA>='$fecha_i' AND FECHA_COMPRA<='$fecha_d'");
		$stmt->execute();
		
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			echo "ID: ".$row["ID_PRODUCTO"].", Nombre: ".$row["NOMBRE"].", Precio: ".$row["PRECIO"].", Unidades: ".$row["UNIDADES"].", Fecha Compra: ".$row["FECHA_COMPRA"];
			echo "<br>";
		}
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	
	$conn = null;
	
	
	
	
}

	




?>



</body>

</html>