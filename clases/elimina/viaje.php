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

$sqlliquidada = "SELECT
IF(tb_viaje.id_liquidacion > 0,'si', 'no') as liquidada
FROM
tb_viaje
WHERE
tb_viaje.id_viaje = '$id'";
$rsliquidada = mysqli_query($conexion, $sqlliquidada);
$datos = mysqli_fetch_array($rsliquidada);
$liquidada = $datos['liquidada'];

if($liquidada == 'no'){
	mysqli_select_db($conexion,'$basedatos');
	$sql = "DELETE FROM tb_viaje WHERE id_viaje = '$id'";
	mysqli_query($conexion,$sql);
	$array=array('success'=>'true');
	echo json_encode($array);

}else{

	$array=array('success'=>'false');
	echo json_encode($array);
}

}
?>