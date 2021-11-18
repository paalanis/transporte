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

	$id=utf8_encode($_REQUEST['dato_id']);
	$desde=$_REQUEST['dato_desde'];
    $hasta=$_REQUEST['dato_hasta'];
    $servicio_r=$_REQUEST['dato_servicior'];
    $transportista=$_REQUEST['dato_transportista'];

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
	$cantidad=$_REQUEST['dato_cantidad'];
	$manual=$_REQUEST['dato_manual'];
	$km=$_REQUEST['dato_km'];
	$obs=utf8_encode($_REQUEST['dato_obs']);
			
	mysqli_select_db($conexion,'$basedatos');
	$sql = "UPDATE tb_viaje
	SET tb_viaje.id_origen = '$origen', tb_viaje.id_destino = '$destino',  tb_viaje.fecha = '$fecha', tb_viaje.id_transportista = '$chofer',
	tb_viaje.id_tipo_remito = '$remito_tipo', tb_viaje.remito = '$remito', tb_viaje.id_servicio = '$servicio', tb_viaje.id_tipo_camion = '$camion', 
	tb_viaje.patente_chasis = '$patente_chasis', tb_viaje.patente_equipo = '$patente_equipo', tb_viaje.id_tipo_carga = '$carga_tipo',
	tb_viaje.cantidad = '$cantidad', tb_viaje.descarga_manual = '$manual', tb_viaje.km = '$km', tb_viaje.obs = '$obs' 
	WHERE
	tb_viaje.id_viaje = '$id'";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true', 'desde'=>$desde, 'hasta'=>$hasta, 'servicio'=>$servicio_r, 'transportista'=>$transportista);
	echo json_encode($array);
		
} //fin else conexion
?>