<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>ALTA PRODUCTOS - JoseTorres</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
$categorias=obtenerCategoria($conn);



if (!isset($_POST) || empty($_POST)) { 

	
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos Producto</div>
<div class="card-body">
		<div class="form-group">
        ID PRODUCTO <input type="text" name="idproducto" placeholder="idproducto" class="form-control">
        </div>
		<div class="form-group">
        NOMBRE PRODUCTO <input type="text" name="nombre" placeholder="nombre" class="form-control">
        </div>
		<div class="form-group">
        PRECIO PRODUCTO <input type="text" name="precio" placeholder="precio" class="form-control">
        </div>
	<div class="form-group">
	<label for="categoria">Categorías:</label>
	<select name="categoria">
		<?php foreach($categorias as $categoria) : ?>
			<option> <?php echo $categoria ?> </option>
		<?php endforeach; ?>
	</select>
	</div>
	</BR>
<?php
	echo '<div><input type="submit" value="Alta Producto"></div>
	</form>';
} else { 

	// Aquí va el código al pulsar submit
    try {
	
		
			// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		obtenerCategoria($conn);
		
		if ($_POST!=null) {
		
			altaCategoria($conn);
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

function obtenerCategoria($conn) {
	
	try {
		$categoria=array();
		$stmt = $conn->prepare("SELECT nombre FROM categoria");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$categoria[]=$row["nombre"];
		}
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	
	$conn = null;
	
	
	
	return $categoria;
	
	
}

function altaCategoria($conn) {
	
	try {
		
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		
		$nombre_categoria=$_POST['categoria'];
		//var_dump($nombre_categoria);
		$stmt = $conn->prepare("SELECT ID_CATEGORIA FROM categoria WHERE NOMBRE='$nombre_categoria'");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$id_categoria=$row["ID_CATEGORIA"];
		}
		
		$id_producto=$_POST['idproducto'];
		$nombre_producto=$_POST['nombre'];
		$precio=$_POST['precio'];
		
		$sql = "INSERT INTO producto (ID_PRODUCTO,NOMBRE,PRECIO,ID_CATEGORIA) VALUES ($id_producto,'$nombre_producto','$precio',$id_categoria)";

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