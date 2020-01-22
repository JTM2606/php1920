<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://bootswatch.com/4/yeti/bootstrap.min.css">
</head>

<body>
    <h1 class="text-center">ALTA DEPARTAMENTO</h1>

	<?php
	/*Inserción en tabla - mysql PDO*/
	require "limpiar.php";

	require "login.php";


	try {
	
		if ($_POST!=null) {
		
		$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

		$nombre=limpiar_campo($_POST['nombreDpto']);
	
		insertarDpto($conn);
		} else {
		
		}
    }
	catch(PDOException $e)
	{
		echo $sql . "<br>" . $e->getMessage();
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
                    <form id="product-form" name="" action="altaDepartamento.php" method="POST" class="card-body">
                        <div class="form-group">
                            <input type="text" name="nombreDpto" placeholder="Nombre" class="form-control">
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
function insertarDpto($conn){
	try {
	
		
		
		$stmt = $conn->prepare("SELECT max(cod_dpto) from departamentos");
		$stmt->execute();
		// set the resulting array to associative
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$nombre=limpiar_campo($_POST['nombreDpto']);
		
		foreach($result as $key=>$c) {
			foreach($c as $key=>$codigo) {
				$cod = substr($codigo , 1 , 3);
				$cod=intval($cod);
				$cod=$cod+1;
				$cod=str_pad($cod,3,"0",STR_PAD_LEFT);
				$cod="D".$cod;
				$sql = "INSERT INTO departamentos (cod_dpto, nombre_dpto) VALUES ('$cod', '$nombre')";
				$conn->exec($sql);
			}
	
		}
		echo "New record created successfully";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }
$conn = null;
}
?>