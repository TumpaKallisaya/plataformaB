<?php

include 'qrlib.php';
 
QRcode::png('code data text', 'archivo.png'); // creates file
QRcode::png('ATT-945'); // creates code image and outputs it directly into browser
	
	
	
function DiasHabiles($fecha_inicial,$fecha_final) { 
	list($dia,$mes,$year) = explode("-",$fecha_inicial); 
	$ini = mktime(0, 0, 0, $mes , $dia, $year); 
	list($diaf,$mesf,$yearf) = explode("-",$fecha_final); 
	$fin = mktime(0, 0, 0, $mesf , $diaf, $yearf); 

	$r = 1; 
	while($ini != $fin) { 
		$ini = mktime(0, 0, 0, $mes , $dia+$r, $year); 
		$newArray[] .=$ini;  
		$r++; 
	} 
	return $newArray; 
}

function Evalua($arreglo) { 
	$feriados        = array( 
		'1-1',  //  A�o Nuevo (irrenunciable) 
		'10-4',  //  Viernes Santo (feriado religioso) 
		'11-4',  //  S�bado Santo (feriado religioso) 
		'1-5',  //  D�a Nacional del Trabajo (irrenunciable) 
		'21-5',  //  D�a de las Glorias Navales 
		'29-6',  //  San Pedro y San Pablo (feriado religioso) 
		'16-7',  //  Virgen del Carmen (feriado religioso) 
		'15-8',  //  Asunci�n de la Virgen (feriado religioso) 
		'18-9',  //  D�a de la Independencia (irrenunciable) 
		'19-9',  //  D�a de las Glorias del Ej�rcito 
		'12-10',  //  Aniversario del Descubrimiento de Am�rica 
		'31-10',  //  D�a Nacional de las Iglesias Evang�licas y Protestantes (feriado religioso) 
		'1-11',  //  D�a de Todos los Santos (feriado religioso) 
		'8-12',  //  Inmaculada Concepci�n de la Virgen (feriado religioso) 
		'13-12',  //  elecciones presidencial y parlamentarias (puede que se traslade al domingo 13) 
		'25-12',  //  Natividad del Se�or (feriado religioso) (irrenunciable) 
	); 

	$j= count($arreglo); 
	$d=0;
	for($i=0;$i<$j;$i++) { 
		$dia = $arreglo[$i]; 
		$fecha = getdate($dia); 
		$feriado = $fecha['mday']."-".$fecha['mon']; 	
		if($fecha["wday"]==0 or $fecha["wday"]==6) 	{ 
			$d++; 
		} 
		elseif(in_array($feriado,$feriados)) {    
			$d++; 
		} 
	} 
	$rlt = $j - $d; 
	return $rlt; 
}



?>