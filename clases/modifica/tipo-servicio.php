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
	$nombre=utf8_encode($_REQUEST['dato_nombre']);
	$horas=$_REQUEST['dato_horas'];
	$tolerancia=$_REQUEST['dato_tolerancia'];
			
	mysqli_select_db($conexion,'$basedatos');
	$sql = "UPDATE tb_tipo_mantenimiento 
			SET tb_tipo_mantenimiento.nombre = '$nombre',
			tb_tipo_mantenimiento.horas = '$horas',
			tb_tipo_mantenimiento.tolerancia = '$tolerancia'
			WHERE
			tb_tipo_mantenimiento.id_tipo_mantenimiento = '$id'";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);
		
} //fin else conexion
?>