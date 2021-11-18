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
	$unidad=$_REQUEST['dato_unidad'];
	$marca=$_REQUEST['dato_marca'];
			
	mysqli_select_db($conexion,'$basedatos');
	$sql = "INSERT INTO tb_insumo (nombre, id_unidad, id_marca)
	VALUES ('$nombre', '$unidad', '$marca')";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);
		
} //fin else conexion
?>