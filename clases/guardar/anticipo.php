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
	//date_default_timezone_set("America/Argentina/Mendoza");
	//$id_global = date("YmdHis");	

	$fecha=$_REQUEST['dato_fecha'];
	$chofer=$_REQUEST['dato_chofer'];
	$anticipo=$_REQUEST['dato_anticipo'];
	$monto=$_REQUEST['dato_monto'];
	$obs=utf8_encode($_REQUEST['dato_obs']);
		
	mysqli_select_db($conexion,'$basedatos');
	$sql = "INSERT INTO tb_anticipo (fecha, id_transportista, id_tipo_anticipo, monto, obs)
	VALUES ('$fecha', '$chofer', '$anticipo', '$monto', '$obs')";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);
		
} //fin else conexion
?>