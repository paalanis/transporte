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
	$id=utf8_encode($_REQUEST['dato_id']);
			
	mysqli_select_db($conexion,'$basedatos');
	$sql = "UPDATE tb_origen
	SET tb_origen.nombre = '$nombre'
	WHERE
	tb_origen.id_origen = '$id'";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);
		
} //fin else conexion
?>