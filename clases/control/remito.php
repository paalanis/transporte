<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../../index.php");
}
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
$remito=$_REQUEST['remito'];
$sqlremito = "SELECT
tb_cosecha.remito
FROM
tb_cosecha
WHERE
tb_cosecha.remito = '$remito'";
$rsremito = mysqli_query($conexion, $sqlremito); 
$filas = mysqli_num_rows($rsremito);
if ($filas > 0) {
$array=array('success'=>'false'); 
echo json_encode($array);
}else{
$array=array('success'=>'true');
echo json_encode($array);
}
?>