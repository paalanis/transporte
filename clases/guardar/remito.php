<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
date_default_timezone_set("America/Argentina/Mendoza");
$id_global = date("Ymdhis");
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
$array=array('success'=>'false');
echo json_encode($array);
}else{
$fecha=$_REQUEST['dato_fecha'];
$insumo=$_REQUEST['dato_insumo'];
$cantidad=$_REQUEST['dato_cantidad'];
$obs=utf8_decode($_REQUEST['dato_obs']);

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

$saldo = $saldo + $cantidad;

mysqli_select_db($conexion,'$basedatos');
$sql = "INSERT INTO tb_stock (fecha, id_insumo, ingreso, id_global, obs, estado, saldo)
VALUES ('$fecha', '$insumo', '$cantidad', '$id_global', '$obs', '1', '$saldo')";
mysqli_query($conexion,$sql);    


$array=array('success'=>'true');
echo json_encode($array);
} //fin else
?>