<?php

function generarCombinacionGanadora() {
$numerosDesordenados=range(1,49);

shuffle($numerosDesordenados);

//$numeroGanador=array();
for ($i=0;$i<7;$i++) {
	$combinacionGanadora[$i]=$numerosDesordenados[$i];
	
}

$combinacionGanadora[count($combinacionGanadora)]=rand(0,9);

//var_dump($combinacionGanadora);

foreach ($combinacionGanadora as $valor) {
	//$numeroGanador.=$valor."-";
}

return $combinacionGanadora;


}




?>