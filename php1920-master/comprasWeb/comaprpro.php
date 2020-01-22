<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>Aprovisionar PRODUCTOS - JoseTorres</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
$productos=obtenerProductos($conn);
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
	<label for="producto">Producto:</label>
	<select name="producto">
		<?php foreach($productos as $producto) : ?>
			<option> <?php echo $producto ?> </option>
		<?php endforeach; ?>
	</select>
	</div>
	<label for="almacen">Almacén:</label>
	<select name="almacen">
		<?php foreach($almacenes as $almacen) : ?>
			<option> <?php echo $almacen ?> </option>
		<?php endforeach; ?>
	</select>
	<div class="form-group">
        Cantidad <input type="text" name="cantidad" placeholder="cantidad" class="form-control">
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
		
			almacena($conn);
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
		$stmt = $conn->prepare("SELECT NOMBRE FROM producto");
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

function almacena($conn) {
	
	try {
		
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		
		$nombre_producto=$_POST['producto'];
		$stmt = $conn->prepare("SELECT ID_PRODUCTO FROM producto WHERE NOMBRE='$nombre_producto'");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$id_producto=$row["ID_PRODUCTO"];
		}
		
		$num_almacen=$_POST['almacen'];
		$cantidad=$_POST['cantidad'];
		
		$sql = "INSERT INTO almacena (NUM_ALMACEN,ID_PRODUCTO,CANTIDAD) VALUES ($num_almacen,'$id_producto',$cantidad)";

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

	




?>



</body>

</html>