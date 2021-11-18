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
	$apellido=$_REQUEST['dato_apellido'];
	$dni=$_REQUEST['dato_dni'];
	$usuario=$_REQUEST['dato_usuario'];
	$pass=$_REQUEST['dato_pass'];
			
	mysqli_select_db($conexion,'$basedatos');
	$sql = "UPDATE tb_transportista 
			SET nombre = '$nombre',
			apellido = '$apellido',
			dni = '$dni',
			usuario = '$usuario',
			pass = '$pass'
			WHERE tb_transportista.id_transportista = '$id'";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);
		
} //fin else conexion
?>