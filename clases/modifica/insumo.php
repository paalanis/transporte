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
	$unidad=$_REQUEST['dato_unidad'];
	$marca=$_REQUEST['dato_marca'];
			
	mysqli_select_db($conexion,'$basedatos');
	$sql = "UPDATE tb_insumo 
	SET tb_insumo.nombre = '$nombre',
		tb_insumo.id_unidad = '$unidad',
		tb_insumo.id_marca = '$marca'
		WHERE
		tb_insumo.id_insumo = '$id'";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);
		
} //fin else conexion
?>