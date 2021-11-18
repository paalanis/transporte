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
$sqlhora = "SELECT
IFNULL(Max(tb_horas_maquinaria.hora_actual),'0') as horas
FROM
tb_horas_maquinaria
WHERE
tb_horas_maquinaria.id_maquinaria = '$id'";
$rshora = mysqli_query($conexion, $sqlhora); 
while ($datos = mysqli_fetch_array($rshora)){
$horas=$datos['horas'];}
if ($horas == '0') {
$array=array('success'=>'false'); 
echo json_encode($array);
}else{
$array=array('success'=>'true', 'hora'=>$horas); 
echo json_encode($array);
}	    
?>