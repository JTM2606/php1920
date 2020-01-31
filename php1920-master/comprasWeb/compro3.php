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

	// Aquí va el código al pulsar submit
    try {
	
		
			// set the PDO error mode to exception
		
		
		
		if ($_SESSION!=null) {
			
			comprarProducto($conn);
			
		} else {
			throw new PDOException('SESION INEXISTENTE');
		}
    }
	catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
	

?>

<?php
// Funciones utilizadas en el programa

function comprarProducto($conn) {
	
	try {
		
		$nif=null;
		
		
		$nombre=$_SESSION['usuario'];
		$nif=$_SESSION['nif'];
		$fecha_compra=$_SESSION['fecha_compra'];
		$id_producto=null;
		$unidades=null;
		
		
		foreach($_SESSION['carrito'] as $producto) {
			
			$id_producto=$producto["id_producto"];
			$unidades=$producto["unidades"];
			
			$cantidad_compra=$unidades;
			
			$sql = "INSERT INTO COMPRA (NIF,ID_PRODUCTO,FECHA_COMPRA,UNIDADES) VALUES ('$nif','$id_producto','$fecha_compra',$unidades)";
			$conn->exec($sql);
			
			$stmt = $conn->prepare("SELECT NUM_ALMACEN,CANTIDAD FROM almacena WHERE ID_PRODUCTO='$id_producto' AND CANTIDAD=(SELECT MAX(CANTIDAD) FROM almacena WHERE id_producto=$id_producto)");

			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			
			foreach($stmt->fetchAll() as $row) {
				$num_almacen=$row["NUM_ALMACEN"];
				$cantidad_almacen=$row["CANTIDAD"];
			}
			
			if ($cantidad_almacen>=$cantidad_compra) {
				$sql = "UPDATE almacena SET CANTIDAD=CANTIDAD-$cantidad_compra WHERE NUM_ALMACEN=$num_almacen AND ID_PRODUCTO='$id_producto'";
			} else {
				throw new PDOException('CANTIDAD EXCESIVA');
			}
			
			
			

			// use exec() because no results are returned
			$conn->exec($sql);
		
		
			
		}	
		echo "COMPRA REALIZA EXITOSAMENTE<br>";
		
		echo "<a href='login.php'>Volver a la Pagina Inicio</a>";
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