<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
$array=array('success'=>'false');
echo json_encode($array);
}else{
$fecha=$_REQUEST['dato_fecha'];
$insumo=$_REQUEST['dato_insumo'];
$cantidad=$_REQUEST['dato_insumo-cantidad'];
$sqlsaldo = "SELECT
				tb_stock.id_stock AS id,
				tb_stock.saldo AS saldo
				FROM
				tb_stock
				WHERE
				tb_stock.id_insumo = '$insumo'
				ORDER BY
				tb_stock.id_stock DESC
				LIMIT 1";
$rssaldo = mysqli_query($conexion, $sqlsaldo);
$datos = mysqli_fetch_array($rssaldo);
$saldo=utf8_encode($datos['saldo']);
$saldo = $saldo - $cantidad;
mysqli_select_db($conexion,'$basedatos');
$sql = "INSERT INTO tb_stock (id_insumo, fecha, egreso, saldo, estado)
VALUES ('$insumo', '$fecha', '$cantidad', '$saldo', '0')";
mysqli_query($conexion,$sql);    
$array=array('success'=>'true');
echo json_encode($array);
}
?>