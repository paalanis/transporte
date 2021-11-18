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

	$fecha=$_REQUEST['dato_fecha'];
	$maquinaria=$_REQUEST['dato_maquinaria'];
	$hora_anterior=$_REQUEST['dato_hora-anterior'];
	$hora_actual=$_REQUEST['dato_hora-actual'];
	$danio=$_REQUEST['dato_danio'];
	$obs=utf8_encode($_REQUEST['dato_obs']);
		
	mysqli_select_db($conexion,'$basedatos');
	$sql = "INSERT INTO tb_horas_maquinaria (fecha, id_maquinaria, hora_anterior, hora_actual, danio, obs)
	VALUES ('$fecha', '$maquinaria', '$hora_anterior', '$hora_actual', '$danio', '$obs')";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);
		
} //fin else conexion
?>