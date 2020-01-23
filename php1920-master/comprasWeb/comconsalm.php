<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>Comprobar Almacen - JoseTorres</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
$almacenes=obtenerAlmacenes($conn);

if (!isset($_POST) || empty($_POST)) { 

	
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-body">
	<div class="form-group">
	<label for="almacen">Almacenes:</label>
	<select name="almacen">
		<?php foreach($almacenes as $almacen) : ?>
			<option> <?php echo $almacen ?> </option>
		<?php endforeach; ?>
	</select>
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
		
			listarProductos($conn);
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

function obtenerAlmacenes($conn) {
	
	try {
		$almacen=array();
		$stmt = $conn->prepare("SELECT NUM_ALMACEN FROM almacen");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$almacen[]=$row["NUM_ALMACEN"];
		}
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	
	$conn = null;
	
	
	
	return $almacen;
	
	
}

function listarProductos($conn) {
	
	try {
		$num_almacen=$_POST['almacen'];
		
		/*$id_producto[]=null;
		$stmt = $conn->prepare("SELECT ID_PRODUCTO FROM almacena WHERE NUM_ALMACEN='$num_almacen'");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$id_producto[]=$row["ID_PRODUCTO"];
		}*/
		
	$stmt = $conn->prepare("SELECT producto.*,almacena.CANTIDAD FROM producto,almacena where producto.ID_PRODUCTO=almacena.ID_PRODUCTO AND NUM_ALMACEN='$num_almacen'");
		$stmt->execute();
		
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			echo "ID: ".$row["ID_PRODUCTO"].", Nombre: ".$row["NOMBRE"].", Precio: ".$row["PRECIO"].", Categoria: ".$row["ID_CATEGORIA"].", Cantidad: ".$row["CANTIDAD"];
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