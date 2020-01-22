<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fecha Empleado</title>
    <!--<link rel="stylesheet" href="https://bootswatch.com/4/yeti/bootstrap.min.css"> -->
</head>

<body>
    <h1 class="text-center">Fecha Empleado</h1>

	<?php
	/*Inserción en tabla - mysql PDO*/
	require "limpiar.php";

	require "login.php";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
		
		
		
		if ($_POST!=null) {
		
			fechaEmpleados($conn);
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
                    <form id="product-form" name="" action="fechaEmpleado.php" method="POST" class="card-body">
						<div class="form-group">
                            Fecha:<input type="text" name="fecha_ini" placeholder="Fecha Inicio" class="form-control">
                        </div>
                        
                        
                        


                        <input type="submit" value="Consultar" class="btn btn-outline-info btn-inline">
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


function fechaEmpleados($conn) {
	
	try {
		
			// set the PDO error mode to exception
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$fecha_ini=limpiar_campo($_POST['fecha_ini']);
			
			$sql = "select dni,cod_dpto from emple_depart WHERE fecha_ini='$fecha_ini'";

			// Prepare statement
			$stmt = $conn->prepare($sql);

			// execute the query
			$stmt->execute();
			
			foreach($stmt->fetchAll() as $row) {
				echo "DNI: ".limpiar_campo($row["dni"]).", Codigo Departamento: ".limpiar_campo($row["cod_dpto"]);
				echo "<br>";
			}
			
			
		
	}
		
	catch(PDOException $e)
		{
		echo $sql . "<br>" . $e->getMessage();
		}

	$conn = null;
	
}
	




?>
