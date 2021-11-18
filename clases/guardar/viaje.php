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
	$id_global = date("YmdHis");	

	$origen=$_REQUEST['dato_origen'];
	$destino=$_REQUEST['dato_destino'];
	$fecha=$_REQUEST['dato_fecha'];
	$chofer=$_REQUEST['dato_chofer'];
	$remito=$_REQUEST['dato_remito'];
	$remito_tipo=$_REQUEST['dato_remito-tipo'];
	$servicio=$_REQUEST['dato_servicio'];
	$camion=$_REQUEST['dato_camion'];
	$patente_chasis=$_REQUEST['dato_patente-1'];
	$patente_equipo=$_REQUEST['dato_patente-2'];
	$carga_tipo=$_REQUEST['dato_carga-tipo'];	
	$cantidad=utf8_encode($_REQUEST['dato_cantidad']);
	$manual=utf8_encode($_REQUEST['dato_manual']);
	$km=$_REQUEST['dato_km'];
	$obs=utf8_encode($_REQUEST['dato_obs']);
		
	mysqli_select_db($conexion,'$basedatos');
	$sql = "INSERT INTO tb_viaje (id_origen, id_destino, fecha, id_transportista, remito, id_tipo_remito, id_servicio, id_tipo_camion, patente_chasis, patente_equipo, id_tipo_carga, cantidad, descarga_manual, km, obs, id_global)
	VALUES ('$origen', '$destino','$fecha', '$chofer', '$remito', '$remito_tipo', '$servicio', '$camion', '$patente_chasis', '$patente_equipo', '$carga_tipo', '$cantidad', '$manual', '$km', '$obs', '$id_global')";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);
		
} //fin else conexion
?>