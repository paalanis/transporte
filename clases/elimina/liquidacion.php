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
$sql = "DELETE FROM tb_liquidacion WHERE id_liquidacion = '$id'";
mysqli_query($conexion,$sql);

mysqli_select_db($conexion,'$basedatos');
$sql1 = "UPDATE tb_viaje SET tb_viaje.id_liquidacion = '', tb_viaje.precio = '' WHERE tb_viaje.id_liquidacion = '$id'";
mysqli_query($conexion,$sql1); 

mysqli_select_db($conexion,'$basedatos');
$sql2 = "UPDATE tb_anticipo SET tb_anticipo.id_liquidacion = '' WHERE tb_anticipo.id_liquidacion = '$id'";
mysqli_query($conexion,$sql2);

$array=array('success'=>'true');
echo json_encode($array);

}
?>




