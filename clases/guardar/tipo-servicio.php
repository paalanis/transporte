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

	$nombre=utf8_encode($_REQUEST['dato_nombre']);
	$horas=$_REQUEST['dato_horas'];
	$tolerancia=$_REQUEST['dato_tolerancia'];
			
	mysqli_select_db($conexion,'$basedatos');
	$sql = "INSERT INTO tb_tipo_servicio (nombre, horas, tolerancia)
	VALUES ('$nombre', '$horas', '$tolerancia')";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);
		
} //fin else conexion
?>