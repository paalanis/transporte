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
	$id_insumo=$_REQUEST['id_insumo'];

// Primero eliminamos el remito seleccionado

		mysqli_select_db($conexion,'$basedatos');
		$sql = "DELETE FROM tb_stock WHERE id_stock = '$id'";
		mysqli_query($conexion,$sql);

		
// Luego buscamos el saldo anterior al remito eliminado

		$sqlsaldo = "SELECT
					tb_stock.saldo as saldo_anterior
					FROM
					tb_stock
					INNER JOIN tb_insumo ON tb_stock.id_insumo = tb_insumo.id_insumo
					WHERE
					tb_stock.id_stock IN ((SELECT 
					MAX(tb_stock.id_stock ) 
					FROM 
					tb_stock 
					WHERE
					tb_stock.id_stock < $id
					GROUP BY 
					tb_stock.id_insumo)) AND
					tb_stock.id_insumo = $id_insumo
					";
		$rssaldo = mysqli_query($conexion, $sqlsaldo);
		$cantidad =  mysqli_num_rows($rssaldo);

		if ($cantidad > 0) { // si existen saldo con de esa finca se muestran, de lo contrario queda en blanco  

		$datos = mysqli_fetch_array($rssaldo);
		$saldo_anterior=utf8_encode($datos['saldo_anterior']);

		}else{

			$saldo_anterior=0;
		}

// Buscamos los ingresos y egresos posteriores al remito eliminado y actualizamos saldos

		$sql_ing_eg = "SELECT
						tb_stock.id_stock as id_consumo,	
						tb_stock.ingreso as ingreso,
						tb_stock.egreso as egreso
						FROM
						tb_stock
						WHERE
						tb_stock.id_insumo = $id_insumo AND
						tb_stock.id_stock > $id
						";
		$rs_ing_eg = mysqli_query($conexion, $sql_ing_eg);
		$cantidad =  mysqli_num_rows($rs_ing_eg);

		if ($cantidad > 0) { // si existen _ing_eg con de esa finca se muestran, de lo contrario queda en blanco  

		 while ($datos = mysqli_fetch_array($rs_ing_eg)){
			$ingreso=utf8_encode($datos['ingreso']);
			$egreso=utf8_encode($datos['egreso']);
			$id_consumo=utf8_encode($datos['id_consumo']);  


			$saldo_actual = $saldo_anterior + $ingreso - $egreso;

			$saldo_anterior = $saldo_actual;

			mysqli_select_db($conexion,'$basedatos');
			$sql = "UPDATE tb_stock
					   SET tb_stock.saldo = $saldo_actual
					   WHERE tb_stock.id_stock = $id_consumo"; 
			mysqli_query($conexion, $sql);	

		 }
		}


$array=array('success'=>'true');
echo json_encode($array);
}
?>