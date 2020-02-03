<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>Comprobar Stock - JoseTorres</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
$productos=obtenerProductos($conn);

if (!isset($_POST) || empty($_POST)) { 

	
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-body">
	<div class="form-group">
	<label for="producto">Producto:</label>
	<select name="producto">
		<?php foreach($productos as $producto) : ?>
			<option> <?php echo $producto ?> </option>
		<?php endforeach; ?>
	</select>
	</div>
	</div>
	</BR>
<?php
	echo '<div><input type="submit" value="Asignar"></div>
	</form>';
} else { 

	// Aquí va el código al pulsar submit
    try {
	
		
			// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		
		if ($_POST!=null) {
		
			listarStock($conn);
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

function obtenerProductos($conn) {
	
	try {
		$producto=array();
		$stmt = $conn->prepare("SELECT NOMBRE FROM PRODUCTO");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$producto[]=$row["NOMBRE"];
		}
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	
	$conn = null;
	
	
	
	return $producto;
	
	
}

function listarStock($conn) {
	
	try {
		$nombre_producto=$_POST['producto'];
		
		$id_producto=null;
		$stmt = $conn->prepare("SELECT ID_PRODUCTO FROM PRODUCTO WHERE NOMBRE='$nombre_producto'");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$id_producto=$row["ID_PRODUCTO"];
		}
		
$stmt = $conn->prepare("SELECT ID_PRODUCTO,CANTIDAD,NUM_ALMACEN FROM ALMACENA where ID_PRODUCTO='$id_producto'");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			echo "ID: ".$row["ID_PRODUCTO"].", Nombre: ".$nombre_producto.", Cantidad: ".$row["CANTIDAD"].", Numero Almacen: ".$row["NUM_ALMACEN"];
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