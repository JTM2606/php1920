<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista Departamento</title>
    <!--<link rel="stylesheet" href="https://bootswatch.com/4/yeti/bootstrap.min.css"> -->
</head>

<body>
    <h1 class="text-center">Lista Departamento</h1>

	<?php
	/*Inserción en tabla - mysql PDO*/
	require "limpiar.php";

	require "login.php";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		
		
		$departamentos=obtenerDepartamentos($conn);
		
		if ($_POST!=null) {
			listarEmpleados($conn);
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
                    <form id="product-form" name="" action="listaDepartamento.php" method="POST" class="card-body">
						<div class="form-group">
							Departamento
                            <select name="departamento">
								<?php foreach($departamentos as $departamento) : ?>
									<option> <?php echo $departamento ?> </option>
								<?php endforeach; ?>
							</select>
                        </div>
                        
                        
                        


                        <input type="submit" value="Consultar Empleados" class="btn btn-outline-info btn-inline">
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

function listarEmpleados($conn) {
	
	try {
		$nombre_dpto=limpiar_campo($_POST['departamento']);
		
		
		
		$stmt = $conn->prepare("SELECT empleados.* FROM empleados,emple_depart,departamentos 
								where empleados.dni=emple_depart.dni AND departamentos.cod_dpto=emple_depart.cod_dpto AND departamentos.nombre_dpto='$nombre_dpto' AND emple_depart.fecha_fin IS NULL");
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			echo "DNI: ".limpiar_campo($row["dni"]).", Nombre: ".limpiar_campo($row["nombre"]).", Apellido: ".limpiar_campo($row["apellido"]).", Salario: ".limpiar_campo($row["salario"]);
			echo "<br>";
		}
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	
	$conn = null;
	
	
	
	
}

	




?>
