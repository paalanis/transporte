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

	$origen=$_REQUEST['dato_origen'];
	$destino=$_REQUEST['dato_destino'];
	$camion=$_REQUEST['dato_camion'];
	$precio=$_REQUEST['dato_precio'];
	$obs=utf8_encode($_REQUEST['dato_obs']);
		
	mysqli_select_db($conexion,'$basedatos');
	$sql = "INSERT INTO tb_lista_precios (id_origen, id_destino, id_tipo_camion, precio, obs)
	VALUES ('$origen', '$destino', '$camion', '$precio', '$obs')";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);
		
} //fin else conexion
?>