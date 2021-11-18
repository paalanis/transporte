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
$id=$_REQUEST['id'];
$sqldni = "SELECT
IFNULL(tb_transportista.dni,'0') AS dni
FROM
tb_transportista
WHERE
tb_transportista.id_transportista = '$id'";
$rsdni = mysqli_query($conexion, $sqldni); 
while ($datos = mysqli_fetch_array($rsdni)){
$dnis=$datos['dni'];}
if ($dnis == '0') {
$array=array('success'=>'false'); 
echo json_encode($array);
}else{
$array=array('success'=>'true', 'dni'=>$dnis); 
echo json_encode($array);
}	    
?>