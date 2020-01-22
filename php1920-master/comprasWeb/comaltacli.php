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

	
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos</div>
<div class="card-body">
		<div class="form-group">
        NIF <input type="text" name="nif" placeholder="nif" class="form-control">
        </div>
		<div class="form-group">
        NOMBRE <input type="text" name="nombre" placeholder="nombre" class="form-control">
        </div>
		<div class="form-group">
        APELLIDO <input type="text" name="apellido" placeholder="apellido" class="form-control">
        </div>
		<div class="form-group">
        CODIGO POSTAL <input type="text" name="cp" placeholder="codigo postal" class="form-control">
        </div>
		<div class="form-group">
        DIRECCION <input type="text" name="direccion" placeholder="direccion" class="form-control">
        </div>
		<div class="form-group">
        CIUDAD <input type="text" name="ciudad" placeholder="ciudad" class="form-control">
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
		$nombre=$_POST['nombre'];
		$apellido=$_POST['apellido'];
		$cp=$_POST['cp'];
		$direccion=$_POST['direccion'];
		$ciudad=$_POST['ciudad'];
		
		$sql = "INSERT INTO CLIENTE (NIF,NOMBRE,APELLIDO,CP,DIRECCION,CIUDAD) VALUES ('$nif','$nombre','$apellido',$cp,'$direccion','$ciudad')";

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

function comprobarDNI($conn) {
	
	$resultado=true;
	
	try {
		
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$nif=$_POST['nif'];
		$nif_2=null;
		
		$stmt = $conn->prepare("SELECT NIF FROM CLIENTE WHERE NIF='$nif'");
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