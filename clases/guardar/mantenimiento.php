<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
	$array=array('success'=>'false');
	echo json_encode($array);
	exit();
}else{

	date_default_timezone_set("America/Argentina/Mendoza");
	$id_global = date("Ymdhis");	

	$fecha=$_REQUEST['dato_fecha'];
	$maquinaria=$_REQUEST['dato_maquinaria'];
	$hora_actual=$_REQUEST['dato_hora-anterior'];
	$obs=utf8_decode($_REQUEST['dato_obs']);
	
	$idinicial=$_REQUEST['dato_id-inicial']; // id inicial de cuarteles
	$idfinal=$_REQUEST['dato_id-final'];


	for ($i=$idinicial; $i <=$idfinal ; $i++) { 

		$id_service=$_REQUEST['dato_service-'.$i.''];
		
		if ($id_service == 0)
		        continue;

		mysqli_select_db($conexion,'$basedatos');
		$sql = "INSERT INTO tb_mantenimiento (id_global, id_maquinaria, fecha, hora_actual, obs, id_tipo_mantenimiento)
		VALUES ('$id_global', '$maquinaria', '$fecha', '$hora_actual', '$obs', '$id_service')";
		mysqli_query($conexion,$sql); 

		mysqli_select_db($conexion,'basedatos');
		$sqlagregaparte = "UPDATE tb_stock
	    SET tb_stock.id_global = '$id_global'
	    WHERE tb_stock.estado = '0'"; 
		mysqli_query($conexion, $sqlagregaparte);

    	mysqli_select_db($conexion,'basedatos');
		$sqlinsumo = "UPDATE tb_stock SET tb_stock.estado = '1' WHERE tb_stock.estado = '0'"; 
		mysqli_query($conexion, $sqlinsumo);

		
	}

		$array=array('success'=>'true');
		echo json_encode($array);   

} //fin else conexion



?>