<?php
session_start();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web Pedidos</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>Realiza tu Pedido - JoseTorres</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
$productos=obtenerProductos($conn);

var_dump($_SESSION);

$fecha_compra=date('Y-m-d');

$_SESSION['fechaPedido'] = $fecha_compra;
$_SESSION['fechaSolicitud'] = $fecha_compra;
$_SESSION['fechaEnvio'] = null;


if (!isset($_POST) || empty($_POST)) { 

	
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos Producto</div>
<div class="card-body">
<?php

	if (!isset($_SESSION['numeroPago'])) {
		echo '<div class="form-group">
        Número de Pago <input type="text" name="checkNumber" placeholder="checkNumber" class="form-control">
        </div>';
		
	}

?>
	<div class="form-group">
	<label for="producto">Productos:</label>
	<select name="producto">
		<?php foreach($productos as $producto) : ?>
			<option> <?php echo $producto ?> </option>
		<?php endforeach; ?>
	</select>
	</div>
	<div class="form-group">
       Unidades <input type="text" name="unidades" placeholder="unidades" class="form-control">
    </div>
	</BR>
<?php
	echo '<div><input name="añadir" type="submit" value="Añadir Articulo" ></div>
	</form>';
	echo '<div><input name="finalizar" type="submit" value="Finalizar Compra"></div>';
} else { 

	// Aquí va el código al pulsar submit
    try {
	
		
		
		if ($_POST!=null) {
			if (isset($_POST["finalizar"])) {
				finalizarCompra($conn);
			} else if ($_POST["añadir"]) {
				añadirArticulo($conn);
			}
				
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
		$stmt = $conn->prepare("SELECT * FROM products WHERE quantityInStock>=0");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$producto[]=$row["productName"];
		}
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	
	$conn = null;
	
	
	
	return $producto;
	
	
}

function añadirArticulo($conn) {
	
	try {
	
		if (isset($_SESSION['numeroPago'])) {
			$numeroPago=$_SESSION['numeroPago'];
		} else {
			$numeroPago=$_POST['checkNumber'];
		}
		if (!empty($numeroPago)) {
			
			$letras=substr($numeroPago,0,2);
			$numeros=substr($numeroPago,2);
			
			if (ctype_alpha($letras) && ctype_digit($numeros)) {
				
				$_SESSION['numeroPago']=$numeroPago;
				
				
				if (!isset($_SESSION['carrito'])) {
					$_SESSION['carrito']=array();
				}
				
				$codProducto=null;
				$precioProducto=null;
				$producto=$_POST['producto'];
				$stmt = $conn->prepare("SELECT productCode,buyPrice FROM products WHERE productName LIKE '$producto'");
				$stmt->execute();
				
				$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
				
				foreach($stmt->fetchAll() as $row) {
					$codProducto=$row["productCode"];
					$precioProducto=$row["buyPrice"];
				}
				
				
				
				$array_productos=array();
		
				$array_productos['cod_producto']=$codProducto;
				$array_productos['unidades']=intval($_POST['unidades']);
				$array_productos['precio']=intval($precioProducto);
				
				$i=0;
				$repetido=false;
				var_dump($array_productos);
				while ($i<count($_SESSION['carrito']) && $repetido==false) {
					
					if ($_SESSION['carrito'][$i]['cod_producto']==$array_productos['cod_producto']) {
						
						$_SESSION['carrito'][$i]['unidades']+=$array_productos['unidades'];
						
						$repetido=true;
					}
					$i++;
				}
				
				if ($repetido==false) {
					array_push($_SESSION['carrito'],$array_productos);
				}
				
				
			} else {
				throw new PDOException('Formato de Numero de Pago Inválido');
			}
			
		} else {
			throw new PDOException('Numero de Pago Vacio');
		}
		
		
		}
		
	catch(PDOException $e)
		{
		echo "Error: " . $e->getMessage();
		}

	$conn = null;
	
}

function finalizarCompra($conn) {
	
	try {
		
		
		$_SESSION['fechaEnvio']=date('Y-m-d');
		
		$paymentDate=$_SESSION['fechaEnvio'];
		
		$customerNumber=$_SESSION['id'];
		$checkNumber=$_SESSION['numeroPago'];
		$amount=null;
		
		foreach($_SESSION['carrito'] as $producto) {
			
			$productCode=$producto["cod_producto"];
			$quantityInStock_compra=$producto["unidades"];
			
			
			
			$stmt = $conn->prepare("SELECT PRODUCTCODE,QUANTITYINSTOCK FROM PRODUCTS WHERE PRODUCTCODE='$productCode' AND QUANTITYINSTOCK=quantityInStock");

			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			
			foreach($stmt->fetchAll() as $row) {
				$productCode=$row["PRODUCTCODE"];
				$quantityInStock_almacen=$row["QUANTITYINSTOCK"];
			}
			
			if ($quantityInStock_almacen>=$quantityInStock_compra) {
				$sql = "UPDATE PRODUCTS SET QUANTITYINSTOCK=QUANTITYINSTOCK-$quantityInStock_compra WHERE PRODUCTCODE='$productCode'";
				$conn->exec($sql);
			} else {
				throw new PDOException('CANTIDAD DE COMPRA DE PRODUCTOS EXCESIVA');
			}

			// use exec() because no results are returned
			
		}
		
			foreach($_SESSION['carrito'] as $producto) {
				$amount+=($producto['precio']*$producto['unidades']);
			}
			
			$sql = "INSERT INTO PAYMENTS (CUSTOMERNUMBER,CHECKNUMBER,PAYMENTDATE,AMOUNT) VALUES ('$customerNumber','$checkNumber','$paymentDate',$amount)";
			$conn->exec($sql);
			
			$orderNumber=null;
				
				$stmt = $conn->prepare("SELECT MAX(orderNumber) AS orderNumber FROM ORDERS");

				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				
				foreach($stmt->fetchAll() as $row) {
					$orderNumber=$row["orderNumber"]+1;
				}
				
				$orderDate=$_SESSION['fechaPedido'];
				$requiredDate=$_SESSION['fechaSolicitud'];
				$shippedDate=$paymentDate;			
				
				$customerNumber=$_SESSION['id'];
				
				$sql = "INSERT INTO ORDERS (ORDERNUMBER,ORDERDATE,REQUIREDDATE,SHIPPEDDATE,CUSTOMERNUMBER) VALUES ('$orderNumber','$orderDate','$requiredDate','$shippedDate','$customerNumber')";
				$conn->exec($sql);
				
				$i=0;
				foreach($_SESSION['carrito'] as $producto) {
					
					$orderNumber;
					$productCode=$producto['cod_producto'];
					$quantityOrdered=$producto['unidades'];
					$priceEach=$producto['precio'];
					$orderLineNumber=$i;
					var_dump($orderLineNumber);
					
					$sql = "INSERT INTO ORDERDETAILS (ORDERNUMBER,PRODUCTCODE,QUANTITYORDERED,PRICEEACH,ORDERLINENUMBER) VALUES ('$orderNumber','$productCode','$quantityOrdered','$priceEach','$orderLineNumber')";
					$conn->exec($sql);
					$i++;
				}
			
			
		
			
			
			echo "COMPRA REALIZA EXITOSAMENTE<br>";
			
			echo "<a href='pe_login.php'>Volver a la Pagina Inicio</a>";
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