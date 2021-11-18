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

	$nuevo=utf8_encode($_REQUEST['nuevo']);
	$dato=utf8_encode($_REQUEST['dato']);

	$tabla = 'tb_'.$nuevo;
			
	mysqli_select_db($conexion,'$basedatos');
	$sql = "INSERT INTO $tabla (nombre)
	VALUES ('$dato')";
	mysqli_query($conexion,$sql);

	$array=array('success'=>'true', 'dato'=>$dato);
	echo json_encode($array);
		
} //fin else conexion
?>