<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>ALTA CATEGORÍAS - Nombre del alumno</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
if (!isset($_POST) || empty($_POST)) { 

	
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos Categoría</div>
<div class="card-body">
		<div class="form-group">
        ID CATEGORIA <input type="text" name="idcategoria" placeholder="idcategoria" class="form-control">
        </div>
		<div class="form-group">
        NOMBRE CATEGORIA <input type="text" name="nombre" placeholder="nombre" class="form-control">
        </div>

		</BR>
<?php
	echo '<div><input type="submit" value="Alta Categoría"></div>
	</form>';
} else { 

	// Aquí va el código al pulsar submit
	
	try {
	
		if ($_POST!=null) {
		
		//$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

	
		insertarCategoria($conn);
		} else {
		
		}
    }
	catch(PDOException $e)
	{
		echo $sql . "<br>" . $e->getMessage();
	}

	$conn = null;
	
}
?>

<?php
// Funciones utilizadas en el programa

function insertarCategoria($conn){
	try {
	
		
		$id_categoria=$_POST['idcategoria'];
		$nombre_categoria=$_POST['nombre'];
		
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		
		$sql = "INSERT INTO categoria (ID_CATEGORIA, NOMBRE) VALUES ('$id_categoria', '$nombre_categoria')";
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