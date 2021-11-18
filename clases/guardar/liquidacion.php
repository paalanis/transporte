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


	$sqlultimaliq = "SELECT
	Max(tb_liquidacion.numero) as ultima
	FROM
	tb_liquidacion";
	$rsultimaliq = mysqli_query($conexion, $sqlultimaliq);
	$datos = mysqli_fetch_array($rsultimaliq);
	$ultima = $datos['ultima'];

	$fecha=date("Y-m-d");
	$desde=$_REQUEST['dato_desde'];
	$hasta=$_REQUEST['dato_hasta'];
	$transportista=$_REQUEST['dato_transportista'];
	$servicio=$_REQUEST['dato_servicio'];
	$monto_viajes=$_REQUEST['dato_monto-viajes'];
	$monto_comision=$_REQUEST['dato_monto-comision'];
	$monto_sub1=$_REQUEST['dato_monto-sub1'];
	$monto_iva=$_REQUEST['dato_monto-iva'];
	$monto_sub2=$_REQUEST['dato_monto-sub2'];
	$monto_anticipos=$_REQUEST['dato_monto-anticipo'];
	$monto_sub3=$_REQUEST['dato_monto-sub3'];
	$monto_retencion=$_REQUEST['dato_monto-retencion'];
	$monto_final=$_REQUEST['dato_monto-final'];

	$numero = $ultima + 1;
		
	mysqli_select_db($conexion,'$basedatos');
	$sql = "INSERT INTO tb_liquidacion (id_global, fecha, fecha_i, fecha_f, id_transportista, id_servicio, monto_viajes, monto_comision, monto_iva, monto_anticipos, monto_retencion, monto_final, numero)
	VALUES ('$id_global', '$fecha', '$desde','$hasta', '$transportista', '$servicio', '$monto_viajes', '$monto_comision', '$monto_iva', '$monto_anticipos', '$monto_retencion', '$monto_final', '$numero')";
	mysqli_query($conexion,$sql);    

//Consultamos que ID fue dado y luego actualizamos viajes y anticipos.

	$sqlid = "SELECT
	Max(tb_liquidacion.id_liquidacion) as id
	FROM
	tb_liquidacion";
	$rsid = mysqli_query($conexion, $sqlid);
	$datos = mysqli_fetch_array($rsid);
	$id = $datos['id'];
	
	$idinicial=$_REQUEST['dato_idinicial'];
	$finalanticipo=$_REQUEST['dato_finalanticipo'];
	$inicioviaje=$_REQUEST['dato_inicioviaje'];
	$idfinal=$_REQUEST['dato_idfinal'];

	for ($i=$idinicial; $i <= $finalanticipo; $i++) { 

		$id_anticipo=$_REQUEST['dato_'.$i.''];
		$anticipo=$_REQUEST['dato_aprobado'.$i.''];

		if ($anticipo == 0)
		        continue;

		mysqli_select_db($conexion,'$basedatos');
		$sql2 = "UPDATE tb_anticipo SET tb_anticipo.id_liquidacion = '$id', tb_anticipo.monto = '$anticipo' WHERE tb_anticipo.id_anticipo = '$id_anticipo'";
		mysqli_query($conexion,$sql2); 
	}

	for ($i=$inicioviaje; $i <= $idfinal; $i++) { 

		$id_viaje=$_REQUEST['dato_'.$i.''];
		$viaje=$_REQUEST['dato_aprobado'.$i.''];

		if ($viaje == 0)
		        continue;

		mysqli_select_db($conexion,'$basedatos');
		$sql1 = "UPDATE tb_viaje SET tb_viaje.id_liquidacion = '$id', tb_viaje.precio = '$viaje' WHERE tb_viaje.id_viaje = '$id_viaje'";
		mysqli_query($conexion,$sql1); 
	}


	$array=array('success'=>'true', 'numero'=>$numero, 'dato'=>$id);
	echo json_encode($array);
		
} //fin else conexion
?>