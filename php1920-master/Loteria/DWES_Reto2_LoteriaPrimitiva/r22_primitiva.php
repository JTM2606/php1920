<?php

require "r22_funciones.php";

$numeroGanador=generarCombinacionGanadora();
	//echo $numeroGanador."<br>";
//$numeroGanador=array(25,17,15,32,1,14);
//echo "numero ganador";
//var_dump($numeroGanador);
$participantes=file("r22_primitiva.txt");

//var_dump($participantes);
$fecha=date_create($_POST["fechasorteo"]);
$fecha=date_format($fecha,'d-m-Y');

//$fichero=fopen("premiosorteo_".$fecha.".txt");


fopen("premiosorteo_".$fecha.".txt","a+");
$fichero=fopen("premiosorteo_".$fecha.".txt","c+");
$recaudacion=($_POST["recaudacion"])*0.8;
$premio=$recaudacion*0.05;

fwrite($fichero,"C6#Premio a recibiar cada acertante 6 aciertos,".$recaudacion*0.4 ."\r\n");
fwrite($fichero,"C5+#Premio a recibiar cada acertante 5 aciertos + complemento,".$recaudacion*0.3 ."\r\n");
fwrite($fichero,"C5#Premio a recibiar cada acertante 5 aciertos,".$recaudacion*0.15 ."\r\n");
fwrite($fichero,"C4#Premio a recibiar cada acertante 4 aciertos,".$recaudacion*0.08 ."\r\n");
fwrite($fichero,"C3#Premio a recibiar cada acertante 3 aciertos,".$recaudacion*0.05 ."\r\n");
fwrite($fichero,"CR#Premio a recibiar cada acertante reintegro,".$recaudacion*0.02 ."\r\n");


$arrayNumero=array();

$arrayAcertantes=array();


foreach ($participantes as $i => $valor) {
	//$contador=0;
	$boleto=explode("-",$valor);
	//echo "boleto";
	//var_dump($boleto);
	
	$premio=0;
	for ($j=0;$j<count($boleto)-1;$j++) {
		
		$arrayNumero[$j]=$boleto[$j+1];
		
	}
	
	//echo "numero";
	
	//var_dump($arrayNumero);
	
	$z=0;
	$numeroIncorrecto=false;
	$numeroIguales=0;
	//echo count($numeroGanador);
	while ($z<count($arrayNumero)) {
		//echo "numero a comprobar";
		
		$y=0;
		$numeroCorrecto=false;
		while ($y<count($numeroGanador) && $numeroCorrecto==false) {
			//var_dump($arrayNumero[$z]);
			//var_dump($numeroGanador[$y]);
			
			if ($numeroGanador[$y]==$arrayNumero[$z]) {
				$numeroIguales++;
				$numeroCorrecto=true;
				
			}
			$y++;
		}
		$z++;
	}
	$complemento=false;
	$reintegro=false;
	
	//Comprobar complemento
	if ($numeroGanador[count($numeroGanador)-2]==$arrayNumero[count($arrayNumero)-2]) {
		$complemento=true;
	}
	
	if ($numeroGanador[count($numeroGanador)-1]==$arrayNumero[count($arrayNumero)-1]) {
		$reintegro=true;
	}
	
	
	echo $numeroIguales."<br>";
	switch ($numeroIguales) {
		case 6:
		
			$arrayAcertantes[$numeroIguales]+=1;
			break;
		case 5:
			$arrayAcertantes[$numeroIguales]+=1;
			break;
		case 4:
			$arrayAcertantes[$numeroIguales]+=1;
			break;
		case 3:
			$arrayAcertantes[$numeroIguales]+=1;
			break;
		case 2:
			$arrayAcertantes[$numeroIguales]+=1;
			break;
		case 1:
			$arrayAcertantes[$numeroIguales]+=1;
			break;
		case 0:
			$arrayAcertantes[$numeroIguales]+=1;
			break;
}
	

	
}

var_dump($arrayAcertantes);

echo "Sorteo: ".$fecha."<br>";

for ($i=0;$i<count($numeroGanador);$i++) {
	if ($i<count($numeroGanador)-2) {
	$numeroLoteria[$i]=$numeroGanador[$i];
	echo $numeroLoteria[$i]." ";
	} else if ($i<count($numeroGanador)-1){
		$numeroComplemento=$numeroGanador[$i];
	} else {
		$numeroReintegro=$numeroGanador[$i];
	}
}
echo "<br>";

echo "Complementario: ".$numeroComplemento;

echo "<br>";

echo "Reintegro: ".$numeroReintegro;

echo "<br>";

echo "Apuestas Jugadas: ".(count($participantes)-1);



fclose($fichero);
	
	

	





?>