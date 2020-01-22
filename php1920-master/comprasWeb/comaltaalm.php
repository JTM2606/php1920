<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!--<link rel="stylesheet" href="https://bootswatch.com/4/yeti/bootstrap.min.css">-->
</head>

<body>
    <h1 class="text-center">ALTA Almacen</h1>

	<?php
	/*Inserción en tabla - mysql PDO*/
	require "limpiar.php";
	include "conexion.php";


	try {
	
		if ($_POST!=null) {
		
		$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

	
		altaAlmacen($conn);
		} else {
		
		}
    }
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}

	$conn = null;
?>
	
	
	
    <div class="container ">
        <!--Aplicacion-->
        <div id="App" class="row pt-6">
            <div class="col-md4-">
                <div class="card">
                    <div class="card-header">
                        Datos
                    </div>
                    <form id="product-form" name="" action="comaltaalm.php" method="POST" class="card-body">
                        <div class="form-group">
                            <input type="text" name="localidad" placeholder="Localidad" class="form-control">
                        </div>
                        
                        


                        <input type="submit" value="Alta Almacen" class="btn btn-outline-info btn-inline">
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
function altaAlmacen($conn){
	try {
	
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$stmt = $conn->prepare("SELECT max(NUM_ALMACEN) as MAXIMO from almacen");
		$stmt->execute();
		// set the resulting array to associative
		$cod=0;
		foreach($stmt->fetchAll() as $row) {
			$cod=$row["MAXIMO"];
		}
		
		$localidad=limpiar_campo($_POST['localidad']);
		
		
				
		$cod=$cod+10;
		$sql = "INSERT INTO almacen (NUM_ALMACEN, LOCALIDAD) VALUES ('$cod', '$localidad')";
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