<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Salario Empleado</title>
    <!--<link rel="stylesheet" href="https://bootswatch.com/4/yeti/bootstrap.min.css"> -->
</head>

<body>
    <h1 class="text-center">Salario Empleado</h1>

	<?php
	/*Inserción en tabla - mysql PDO*/
	require "limpiar.php";

	require "login.php";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		
		$dni_empleados=obtenerDni($conn);
		
		
		if ($_POST!=null) {
		
			cambiarSalario($conn);
		} else {
		
		}
    }
	catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
	?>
	
    <div class="container ">
        <!--Aplicacion-->
        <div id="App" class="row pt-6">
            <div class="col-md4-">
                <div class="card">
                    <div class="card-header">
                        Datos
                    </div>
                    <form id="product-form" name="" action="salarioEmpleado.php" method="POST" class="card-body">
						<div class="form-group">
							Dni Empleado
                            <select name="dni">
								<?php foreach($dni_empleados as $dni) : ?>
									<option> <?php echo $dni ?> </option>
								<?php endforeach; ?>
							</select>
                        </div>
						<div class="form-group">
                            <input type="text" name="salarioNuevo" placeholder="%Salario" class="form-control">
                        </div>
                        
                        
                        


                        <input type="submit" value="Cambiar Salario" class="btn btn-outline-info btn-inline">
                        <input type="reset" value="Borrar" class="btn btn-outline-info btn-inline ml-5">
                    </form>
                </div>


            </div>

        </div>
        <br>


    </div>



</body>

</html>

<?php
// Funciones utilizadas en el programa

// Obtengo todos los departamentos para mostrarlos en la lista de valores
function obtenerDepartamentos($conn) {
	
	try {
		$departamentos = array();
		
		$stmt = $conn->prepare("SELECT cod_dpto, nombre_dpto FROM departamentos");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$departamentos[]=limpiar_campo($row["nombre_dpto"]);
		}
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	
	$conn = null;
	
	
	
	return $departamentos;
	
	
}

function obtenerDni($conn) {
	
	try {
		$dni = array();
		
		$stmt = $conn->prepare("SELECT dni FROM empleados");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$dni[]=limpiar_campo($row["dni"]);
		}
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	
	$conn = null;
	
	
	
	return $dni;
	
	
}

function cambiarSalario($conn) {
	
	try {
		
			// set the PDO error mode to exception
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$dni=limpiar_campo($_POST['dni']);
			$salarioNuevo=limpiar_campo($_POST['salarioNuevo']);
			
			$sql = "UPDATE empleados SET salario=salario+(salario*($salarioNuevo/100)) WHERE dni='$dni'";

			// Prepare statement
			$stmt = $conn->prepare($sql);

			// execute the query
			$stmt->execute();
			
		
	}
		
	catch(PDOException $e)
		{
		echo $sql . "<br>" . $e->getMessage();
		}

	$conn = null;
	
}
	




?>
