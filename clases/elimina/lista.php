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
$id=$_REQUEST['id'];
mysqli_select_db($conexion,'$basedatos');
$sql = "DELETE FROM tb_lista_precios WHERE id_lista_precios = '$id'";
mysqli_query($conexion,$sql);
$array=array('success'=>'true');
echo json_encode($array);
}
?>