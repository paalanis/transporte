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
	$apellido=$_REQUEST['dato_apellido'];
	$dni=$_REQUEST['dato_dni'];
			
	mysqli_select_db($conexion,'$basedatos');
	$sql = "INSERT INTO tb_transportista (nombre, apellido, dni)
	VALUES ('$nombre', '$apellido', '$dni')";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);
		
} //fin else conexion
?>