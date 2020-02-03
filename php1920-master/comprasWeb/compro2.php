<?php
session_start();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>Comprar PRODUCTOS - JoseTorres</h1>
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
	</div>
	<div class="form-group">
	<label for="producto">Producto:</label>
	<select name="producto">
		<?php foreach($productos as $producto) : ?>
			<option> <?php echo $producto ?> </option>
		<?php endforeach; ?>
	</select>
	</div>
	<div class="form-group">
        Cantidad <input type="number" name="cantidad" placeholder="cantidad" class="form-control">
        </div>
	</div>
	</div>
	</BR>
<?php
	
	echo '<div><input type="submit" value="Añadir al Carrito"></div>
	</form>';
	echo '<div><a href="compro3.php">Finalizar Producto</div>';
} else { 

	// Aquí va el código al pulsar submit
    try {
	
		
			// set the PDO error mode to exception
		
		
		
		if ($_POST!=null) {
			
			
			
			comprarProducto($conn);
			//var_dump($_SESSION);
			
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

function comprarProducto($conn) {
	
	try {
		
		$nif=null;
		
		
		$nombre=$_SESSION['usuario'];
		
		
		$stmt = $conn->prepare("SELECT NIF FROM REGISTRO WHERE NOMBRE='$nombre'");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$nif=$row["NIF"];
		}
		
		$_SESSION['nif']=$nif;
	
		$fecha_compra=(date("Y")."-".date("m")."-".date("d")."-".date("H")."-".date("i")."-".date("s"));
		$_SESSION['fecha_compra']=$fecha_compra;
		
		$nombre_producto=$_POST['producto'];
		$stmt = $conn->prepare("SELECT ID_PRODUCTO FROM PRODUCTO WHERE NOMBRE='$nombre_producto'");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$id_producto=$row["ID_PRODUCTO"];
		}
		
		
		
		
		$cantidad_compra=$_POST['cantidad'];
		
		$sql = "INSERT INTO COMPRA (NIF,ID_PRODUCTO,FECHA_COMPRA,UNIDADES) VALUES ('$nif','$id_producto','$fecha_compra',$cantidad_compra)";

		// use exec() because no results are returned
		//$conn->exec($sql);
		echo "New record created successfully";
		
		$stmt = $conn->prepare("SELECT NUM_ALMACEN,CANTIDAD FROM ALMACENA WHERE ID_PRODUCTO='$id_producto' AND CANTIDAD=(SELECT MAX(CANTIDAD) FROM almacena WHERE id_producto=$id_producto)");

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$num_almacen=$row["NUM_ALMACEN"];
			$cantidad_almacen=$row["CANTIDAD"];
		}
		
		
		
		
		if ($cantidad_almacen>=$cantidad_compra) {
			$sql = "UPDATE ALMACENA SET CANTIDAD=CANTIDAD-$cantidad_compra WHERE NUM_ALMACEN=$num_almacen AND ID_PRODUCTO='$id_producto'";
		} else {
			throw new PDOException('CANTIDAD EXCESIVA');
		}
		
		
		

		// use exec() because no results are returned
		//$conn->exec($sql);
		
		
		
		
		
		$array_productos=array();
		
		
		$array_productos['id_producto']=$id_producto;
		$array_productos['unidades']=intval($cantidad_compra);
		
		//var_dump($array_productos);
		
		if (empty($_SESSION['carrito'])) {
			$_SESSION['carrito']=array();
		}
		
		$i=0;
		$repetido=false;
		while ($i<count($_SESSION['carrito']) && $repetido==false) {
			
			if ($_SESSION['carrito'][$i]['id_producto']==$array_productos['id_producto']) {
				
				$_SESSION['carrito'][$i]['unidades']+=$array_productos['unidades'];
				
				$repetido=true;
			}
			$i++;
		}
		
		if ($repetido==false) {
			array_push($_SESSION['carrito'],$array_productos);
		}
		
	}
		
	catch(PDOException $e)
		{
		echo "Error: " . $e->getMessage();
		}

	$conn = null;
	
}

	




?>



</body>

</html>