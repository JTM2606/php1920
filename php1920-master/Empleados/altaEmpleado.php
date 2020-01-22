<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alta Empleado</title>
    <link rel="stylesheet" href="https://bootswatch.com/4/yeti/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">ALTA EMPLEADO</h1>

	<?php
	/*Inserción en tabla - mysql PDO*/
	require "limpiar.php";

	require "login.php";

	try {
	
		
	
		$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		$departamentos = obtenerDepartamentos($conn);
		
		if ($_POST!=null) {
		
		altaEmpleado($conn);
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
                    <form id="product-form" name="" action="altaEmpleado.php" method="POST" class="card-body">
						<div class="form-group">
							DNI
                            <input type="text" name="dni" placeholder="dni" class="form-control">
                        </div>
						<div class="form-group">
							Nombre
                            <input type="text" name="nombre" placeholder="nombre" class="form-control">
                        </div>
                        <div class="form-group">
							Apellidos
                            <input type="text" name="apellidos" placeholder="apellidos" class="form-control">
                        </div>
						<div class="form-group">
							Fecha
                            <input type="text" name="fecha" placeholder="fecha nacimiento" class="form-control">
                        </div>
                        <div class="form-group">
							Salario
                            <input type="text" name="salario" placeholder="salario" class="form-control">
                        </div>
						<div class="form-group">
							Departamento
                            <select name="departamento">
								<?php foreach($departamentos as $departamento) : ?>
									<option> <?php echo $departamento ?> </option>
								<?php endforeach; ?>
							</select>
                        </div>
                        
                        
                        


                        <input type="submit" value="Alta Departamento" class="btn btn-outline-info btn-inline">
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

function altaEmpleado($conn) {
	
	try {
		
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$empleado = array();
		
		$nombre=limpiar_campo($_POST['departamento']);
		
		$stmt = $conn->prepare("SELECT cod_dpto FROM departamentos WHERE nombre_dpto='$nombre'");
		$stmt->execute();
		
		$cod_dpto = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach($stmt->fetchAll() as $row) {
			$cod_dpto=limpiar_campo($row["cod_dpto"]);
		}
		
		$dni=limpiar_campo($_POST['dni']);
		$nombre=limpiar_campo($_POST['nombre']);
		$apellidos=limpiar_campo($_POST['apellidos']);
		$fecha_nac=limpiar_campo($_POST['fecha']);
		$salario=limpiar_campo($_POST['salario']);
		$fecha_actual = date_format(date_create(),"Y/m/d");
		
		
		$sql = "INSERT INTO empleados (dni,nombre,apellido,fecha_nac,salario) VALUES ('$dni','$nombre','$apellidos','$fecha_nac',$salario)";

		// use exec() because no results are returned
		$conn->exec($sql);
		echo "New record created successfully, empleados";
		
		$sql = "INSERT INTO emple_depart (dni,cod_dpto,fecha_ini,fecha_fin) VALUES ('$dni','$cod_dpto','$fecha_actual',null)";
		// use exec() because no results are returned
		$conn->exec($sql);
		echo "New record created successfully,emple_depart";
		
		
		}
		
	catch(PDOException $e)
		{
		echo $sql . "<br>" . $e->getMessage();
		}

	$conn = null;
	
}
	




?>
